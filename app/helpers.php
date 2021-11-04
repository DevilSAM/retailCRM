<?php

use Carbon\Carbon;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Обёртка над функцией print_r
 *
 * @param mixed $mVar
 * @param bool  $in_file
 */
function p($mVar = false, $in_file = false)
{
    d($mVar, $in_file);
}

/**
 * Обёртка над функцией print_r
 *
 * @param mixed $mVar
 * @param bool  $in_file
 */
function d($mVar = false, $in_file = false)
{
    if ($in_file) {
        $file = fopen($_SERVER['DOCUMENT_ROOT'] . '/p.log', 'ab');
        if (! $file) {
            print "";
        } else {
            fwrite($file, print_r($mVar, true));
            fwrite($file, "\r\n");
            fclose($file);
        }
    }
    if (! $in_file || $in_file === 2) {
        ?>
        <pre><?php print_r($mVar); ?></pre><?php
    }
}

/**
 * Форматирование цены для отображения
 *
 * @param $price
 * @return string
 */
function priceFormat($price)
{
    return number_format($price, 0, '', ' ') . ' ₽';
}

/**
 * Функция разбирает диапазон дат из строки для дальнейшей работы с ними
 *
 * @param $str
 * @return array
 */
function getDateRange($str)
{
    $dates = explode('—', $str);
    $dates = array_map('trim', $dates);

    $return = [
        'start_date' => '',
        'end_date'   => '',
    ];

    if (! empty($dates[0])) {
        $return['start_date'] = Carbon::parse($dates[0]);
    }

    if (! empty($dates[1])) {
        $return['end_date'] = Carbon::parse($dates[1])->addDay(1)->addSecond(-1);
    }

    return $return;
}

/**
 * Форматирвоание номера телефона
 *
 * @param $phone
 * @return mixed
 */
function formatPhone($phone)
{
    $phoneUtil = PhoneNumberUtil::getInstance();

    try {
        $parse_number = $phoneUtil->parse($phone, 'RU');
        $clean_number = $parse_number->getNationalNumber();

        // Если длина чистого номера - 7 цифр, то считаем его городским и добавляем код города
        if (mb_strlen($clean_number) == 7) {
            $clean_number = '863' . $clean_number;
        }

        if (mb_strlen($clean_number) > 7) {
            $international = PhoneNumber::make($clean_number, 'RU')->formatInternational();

            return $international;
        }

    } catch (NumberParseException $e) {

        // Проблемы с проверкой телефона игнорируем...
        return $phone;
    }

    return $phone;
}

/**
 * Очистка номера телефона от лишних символов
 *
 * @param      $phone
 * @param bool $no_landline
 * @return string|null
 */
function clearPhone($phone, $no_landline = false)
{
    $phoneUtil = PhoneNumberUtil::getInstance();

    try {
        $parse_number = $phoneUtil->parse($phone, 'RU');
        $clean_number = $parse_number->getNationalNumber();

        // Если длина чистого номера - 7 цифр, то считаем его городским и добавляем код города
        if (mb_strlen($clean_number) == 7 && ! $no_landline) {
            $clean_number = '863' . $clean_number;
        }

        return $clean_number;

    } catch (NumberParseException $e) {
        //die($e->getMessage());
    }

    return $phone;
}

/**
 * Функция получает путь к файлам с подстановкой даты модификации для сброса кэша
 *
 * @param      $path
 * @param null $secure
 * @return string
 */
function asset_url($path, $secure = null)
{
    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/public/' . $path;
    $time = '';
    if (is_file($file_path)) {
        $time = filemtime($file_path);
    }

    return app('url')->asset($path . (! empty($time) ? '?' . $time : ''), $secure);
}
