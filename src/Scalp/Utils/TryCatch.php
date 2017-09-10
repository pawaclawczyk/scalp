<?php

declare(strict_types=1);

namespace Scalp\Utils;

abstract class TryCatch
{
    /**
     * Returns `true` if the `TryCatch` is a `Failure`, `false` otherwise.
     *
     * def isFailure: Boolean
     *
     * @return bool
     */
    abstract public function isFailure(): bool;

    /**
     * Returns `true` if the `TryCatch` is a `Success`, `false` otherwise.
     *
     * def isSuccess: Boolean
     *
     * @return bool
     */
    abstract public function isSuccess(): bool;

    /**
     * Returns the value from this `Success` or the given `default` argument if this is a `Failure`.
     *
     * ''Note:'': This will throw an exception if it is not a success and default throws an exception.
     *
     * def getOrElse[U >: T](default: => U): U
     *
     * @param mixed $default
     *
     * @return mixed
     */
    abstract public function getOrElse($default);

    /**
     * Returns this `TryCatch` if it's a `Success` or the given `default` argument if this is a `Failure`.
     *
     * def orElse[U >: T](default: => Try[U]): Try[U]
     *
     * @param TryCatch $default
     *
     * @return TryCatch
     */
    abstract public function orElse(TryCatch $default): TryCatch;

    /**
     * Returns the value from this `Success` or throws the exception if this is a `Failure`.
     *
     * def get: T
     *
     * @return mixed
     */
    abstract public function get();

    /**
     * Applies the given function `f` if this is a `Success`, otherwise returns `Unit` if this is a `Failure`.
     *
     * ''Note:'' If `f` throws, then this method may throw an exception.
     *
     * def foreach[U](f: T => U): Unit
     *
     * @param callable $f
     */
//    abstract public function foreach(callable $f): void;

    /**
     * Returns the given function applied to the value from this `Success` or returns this if this is a `Failure`.
     *
     * def flatMap[U](f: T => Try[U]): Try[U]
     *
     * @param callable $f
     *
     * @return TryCatch
     */
    abstract public function flatMap(callable $f): TryCatch;

    /*
     * Maps the given function to the value from this `Success` or returns this if this is a `Failure`.
     *
     * def map[U](f: T => U): Try[U]
     *
     * @param callable $f
     * @return TryCatch
     */
    abstract public function map(callable $f): TryCatch;

    /*
     * Applies the given partial function to the value from this `Success` or returns this if this is a `Failure`.
     *
     * def collect[U](pf: PartialFunction[T, U]): Try[U]
     *
     * @param callable $f
     * @return TryCatch
     */
//    abstract public function collect(callable $f): TryCatch;

    /*
     * Converts this to a `Failure` if the predicate is not satisfied.
     *
     * def filter(p: T => Boolean): Try[T]
     *
     * @param callable $p
     * @return TryCatch
     */
//    abstract public function filter(callable $p): TryCatch;

    /* Creates a non-strict filter, which eventually converts this to a `Failure`
     *  if the predicate is not satisfied.
     *
     *  Note: unlike filter, withFilter does not create a new Try.
     *        Instead, it restricts the domain of subsequent
     *        `map`, `flatMap`, `foreach`, and `withFilter` operations.
     *
     * As Try is a one-element collection, this may be a bit overkill,
     * but it's consistent with withFilter on Option and the other collections.
     *
     *  [@]param p the predicate used to test elements.
     *  [@]return  an object of class `WithFilter`, which supports
     *             `map`, `flatMap`, `foreach`, and `withFilter` operations.
     *             All these operations apply to those elements of this Try
     *             which satisfy the predicate `p`.
     *
     * [@]inline final def withFilter(p: T => Boolean): WithFilter = new WithFilter(p)
     *
     * @param callable $p
     * @return TryCatch
     */
//    abstract public function withFilter(callable $p): TryCatch;

    /*
     * Applies the given function `f` if this is a `Failure`, otherwise returns this if this is a `Success`.
     * This is like `flatMap` for the exception.
     *
     * def recoverWith[U >: T](@deprecatedName('f) pf: PartialFunction[Throwable, Try[U]]): Try[U]
     *
     * @param callable $pf
     * @return TryCatch
     */
//    abstract public function recoverWith(callable $pf): TryCatch;

    /*
     * Applies the given function `f` if this is a `Failure`, otherwise returns this if this is a `Success`.
     * This is like map for the exception.
     *
     * def recover[U >: T](@deprecatedName('f) pf: PartialFunction[Throwable, U]): Try[U]
     *
     * @param callable $pf
     * @return TryCatch
     */
//    abstract public function recover(callable $pf): TryCatch;

    /*
     * Returns `None` if this is a `Failure` or a `Some` containing the value if this is a `Success`.
     *
     * def toOption: Option[T]
     */
//    abstract public function toOption(): Option;

    /*
     * Transforms a nested `Try`, ie, a `Try` of type `Try[Try[T]]`,
     * into an un-nested `Try`, ie, a `Try` of type `Try[T]`.
     *
     * def flatten[U](implicit ev: T <:< Try[U]): Try[U]
     */
//    abstract public function flatten(): TryCatch;

    /*
     * Inverts this `Try`. If this is a `Failure`, returns its exception wrapped in a `Success`.
     * If this is a `Success`, returns a `Failure` containing an `UnsupportedOperationException`.
     *
     * def failed: Try[Throwable]
     */
//    abstract public function failed(): TryCatch;

    /*
     * Completes this `Try` by applying the function `f` to this if this is of type `Failure`, or conversely, by applying
     * `s` if this is a `Success`.
     *
     * def transform[U](s: T => Try[U], f: Throwable => Try[U]): Try[U]
     */
//    abstract public function transform(callable $s, callable $f): TryCatch;

    /*
     * Returns `Left` with `Throwable` if this is a `Failure`, otherwise returns `Right` with `Success` value.
     *
     * def toEither: Either[Throwable, T]
     */
//    abstract public function toEither(): Either;

    /*
     * Applies `fa` if this is a `Failure` or `fb` if this is a `Success`.
     * If `fb` is initially applied and throws an exception,
     * then `fa` is applied with this exception.
     *
     * @example {{{
     * val result: Try[Throwable, Int] = Try { string.toInt }
     * log(result.fold(
     *   ex => "Operation failed with " + ex,
     *   v => "Operation produced value: " + v
     * ))
     * }}}
     *
     * def fold[U](fa: Throwable => U, fb: T => U): U
     */
//    abstract public function fold(callable $fa, callable $fb);
}
