<?php

namespace App\Http\Cache;

use Closure;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Cache\RateLimiter as LaravelRateLimiter;

class RateLimiter extends LaravelRateLimiter {

	/**
	 * Create a new rate limiter instance.
	 *
	 * @param  \Illuminate\Contracts\Cache\Repository  $cache
	 * @return void
	 */
	public function __construct(Cache $cache) {
		$this->cache = app()->make('cache')->driver(
			app()->config->get('cache.limiter')
		);
	}

	/**
	 * Attempts to execute a callback if it's not limited.
	 *
	 * @param  string  $key
	 * @param  int  $maxAttempts
	 * @param  \Closure  $callback
	 * @param  int  $decaySeconds
	 * @return mixed
	 */
	public function attempt($key, $maxAttempts, Closure $callback, $decaySeconds = 60) {

		if ($this->attempts($key) >= $maxAttempts) {
			$this->cache->add(
				$key . ':timer',
				$this->availableAt($decaySeconds * 3),
				$decaySeconds * 3
			);
		}

		if ($this->tooManyAttempts($key, $maxAttempts)) {
			return false;
		}

		return tap($callback() ?: true, function () use ($key, $decaySeconds) {
			$this->hit($key, $decaySeconds);
		});
	}

	/**
	 * Determine if the given key has been "accessed" too many times.
	 *
	 * @param  string  $key
	 * @param  int  $maxAttempts
	 * @return bool
	 */
	public function tooManyAttempts($key, $maxAttempts) {
		if ($this->cache->has($this->cleanRateLimiterKey($key) . ':timer')) {
			return true;
		}

		return false;
	}

	/**
	 * Increment the counter for a given key for a given decay time.
	 *
	 * @param  string  $key
	 * @param  int  $decaySeconds
	 * @return int
	 */
	public function hit($key, $decaySeconds = 60) {
		$key = $this->cleanRateLimiterKey($key);

		$added = $this->cache->add($key, 0, $decaySeconds);

		$hits = (int) $this->cache->increment($key);

		if (!$added && $hits == 1) {
			$this->cache->put($key, 1, $decaySeconds);
		}

		return $hits;
	}
}
