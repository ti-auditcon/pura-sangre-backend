<?php

namespace App\Models\Clases;

class IconClaseType
{
    /** Clases types */
    public const CROSSFIT = 1;
    public const FUNCTIONAL_TRAINING = 2;
    public const CALISTENIA = 3;
    public const HIIT = 4;
    public const YOGA = 5;
    public const RUNNING = 6;
    public const WHEIGHT_LIFTING = 7;
    public const FOOTBALL = 8;
    public const SPINNING = 9;
    public const BASKETBALL = 10;
    public const ROPE = 11;
    public const CLIMBING = 12;
    public const KICK_BOXING = 13;
    public const FITNESS = 14;
    public const PRE_NATAL = 15;
    public const POST_NATAL = 16;
    public const MUAY_THAI = 17;
    public const MARTIAL_ARTS = 18;
    public const KIDS = 19;
    public const MMA = 20;
    public const BOXING = 21;
    public const BARBELL = 22;

    /**
     * List all icons data for clases types, this includes:
     *     - name:       To store in the database and be compared at the edit select icon
     *     - human_name: To be displayed to a friendly way for the user can understand
     *     - url_path:   The name say it
     *
     * @return  array
     */
    public static function listIcons()
    {
        return [
            self::CROSSFIT => [
                'name'       => 'crossfit',
                'human_name' => 'CrossFit',
                'url_path'   => config('app.api_url') . '/icon/clases/crossfit.svg',
            ],
            self::FUNCTIONAL_TRAINING => [
                'name'       => 'functional_training',
                'human_name' => 'Entrenamiento Funcional',
                'url_path'   => config('app.api_url') . '/icon/clases/functional_training.svg',
            ],
            self::CALISTENIA => [
                'name'       => 'calistenia',
                'human_name' => 'Calistenia',
                'url_path'   => config('app.api_url') . '/icon/clases/calistenia.svg',
            ],
            self::HIIT => [
                'name'       => 'hiit',
                'human_name' => 'HIIT',
                'url_path'   => config('app.api_url') . '/icon/clases/hiit.svg',
            ],
            self::YOGA => [
                'name'       => 'yoga',
                'human_name' => 'Yoga',
                'url_path'   => config('app.api_url') . '/icon/clases/yoga.svg',
            ],
            self::RUNNING => [
                'name'       => 'running',
                'human_name' => 'Running',
                'url_path'   => config('app.api_url') . '/icon/clases/running.svg',
            ],
            self::WHEIGHT_LIFTING => [
                'name'       => 'weightlifting',
                'human_name' => 'Levantamiento de pesas',
                'url_path'   => config('app.api_url') . '/icon/clases/weight.svg',
            ],
            self::FOOTBALL => [
                'name'       => 'football',
                'human_name' => 'Fútbol',
                'url_path'   => config('app.api_url') . '/icon/clases/football.svg',
            ],
            self::SPINNING => [
                'name'       => 'spinning',
                'human_name' => 'Spinning',
                'url_path'   => config('app.api_url') . '/icon/clases/spinning.svg',
            ],
            self::BASKETBALL => [
                'name'       => 'basketball',
                'human_name' => 'Básquetbol',
                'url_path'   => config('app.api_url') . '/icon/clases/basketball.svg',
            ],
            self::ROPE => [
                'name'       => 'rope',
                'human_name' => 'Cuerda',
                'url_path'   => config('app.api_url') . '/icon/clases/rope.svg',
            ],
            self::CLIMBING => [
                'name'       => 'climbing',
                'human_name' => 'Escalada',
                'url_path'   => config('app.api_url') . '/icon/clases/climbing.svg',
            ],
            self::KICK_BOXING => [
                'name'       => 'kick_boxing',
                'human_name' => 'Kick boxing',
                'url_path'   => config('app.api_url') . '/icon/clases/kick_boxing.svg',
            ],
            self::FITNESS => [
                'name'       => 'fitness',
                'human_name' => 'Funcional',
                'url_path'   => config('app.api_url') . '/icon/clases/fitness.svg',
            ],
            self::PRE_NATAL => [
                'name'       => 'pre_natal',
                'human_name' => 'Pre natal',
                'url_path'   => config('app.api_url') . '/icon/clases/pre_natal.svg',
            ],
            self::POST_NATAL => [
                'name'       => 'post_natal',
                'human_name' => 'Post natal',
                'url_path'   => config('app.api_url') . '/icon/clases/post_natal.svg',
            ],
            self::MUAY_THAI => [
                'name'       => 'muay_thai',
                'human_name' => 'Muay Thai',
                'url_path'   => config('app.api_url') . '/icon/clases/muay_thai.svg',
            ],
            self::MARTIAL_ARTS => [
                'name'       => 'martial_arts',
                'human_name' => 'Artes marciales',
                'url_path'   => config('app.api_url') . '/icon/clases/martial_arts.svg',
            ],
            self::KIDS => [
                'name'       => 'kids',
                'human_name' => 'Niños',
                'url_path'   => config('app.api_url') . '/icon/clases/kids.svg',
            ],
            self::MMA => [
                'name'       => 'mma',
                'human_name' => 'Artes marciales mixtas',
                'url_path'   => config('app.api_url') . '/icon/clases/mma.svg',
            ],
            self::BOXING => [
                'name'       => 'boxing',
                'human_name' => 'Boxeo',
                'url_path'   => config('app.api_url') . '/icon/clases/boxing.svg',
            ],
            self::BARBELL => [
                'name'       => 'barbell',
                'human_name' => 'Barbell',
                'url_path'   => config('app.api_url') . '/icon/clases/barbell.svg',
            ],
        ];
    }

    /**
     * Get the icon data for a specific type
     *
     * @return  array
     */
    public static function list()
    {
        $list = self::listIcons();

        /** Sort all clases types by human_name */
        uasort($list, fn($a, $b) => $a['human_name'] <=> $b['human_name']);

        return $list;
    }
}
