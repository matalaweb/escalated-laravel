<?php

namespace Escalated\Laravel;

class Escalated
{
    /**
     * The user model class.
     */
    public static string $userModel = 'App\\Models\\User';

    /**
     * Set the user model class.
     */
    public static function useUserModel(string $model): void
    {
        static::$userModel = $model;
    }

    /**
     * Get the user model class.
     */
    public static function userModel(): string
    {
        return config('escalated.user_model', static::$userModel);
    }

    /**
     * Create a new user model instance.
     */
    public static function newUserModel(): mixed
    {
        $model = static::userModel();

        return new $model;
    }

    /**
     * Get the table prefix.
     */
    public static function tablePrefix(): string
    {
        return config('escalated.table_prefix', 'escalated_');
    }

    /**
     * Get the prefixed table name.
     */
    public static function table(string $name): string
    {
        return static::tablePrefix().$name;
    }
}
