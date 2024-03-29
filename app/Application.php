<?php
namespace App;

use Igaster\LaravelTheme\Facades\Theme;
use Illuminate\Support\Facades\Auth;

/**
 * Class Application
 * @package app
 *
 * This represents our top level processmaker application.
 */
final class Application extends \Illuminate\Foundation\Application
{
    // Our app Version
    public const VERSION = '4.0.0';

    /**
     * Sets the timezone for the application and for php with the specified timezone
     * @param $tz string The timezone to set to
     */
    public function setTimezone($tz): void
    {
        config(['app.timezone' => $tz]);
        date_default_timezone_set(config('app.timezone'));
    }

    /**
     * Retrieves the currently set timezone
     * @return string The timezone for the system
     */
    public function getTimezone()
    {
        return config('app.timezone');
    }

    /**
     * Return the System defined constants and Application variables
     *   Constants: SYS_*
     *   Sessions : USER_* , URS_*
     *
     * @note: This is ported from Gulliver System. This will most likely need to be refactored/removed
     * @return array{SYS_LANG: string, SYS_SKIN: mixed, APPLICATION: mixed, PROCESS: mixed, TASK: mixed, INDEX: mixed, USER_LOGGED: mixed, USER_USERNAME: mixed} Contents of system contents.
     */
    public function getSystemConstants(): array
    {
        $sysCon = [];
        $sysCon['SYS_LANG'] = $this->getLocale();

        // Get the current theme
        $sysCon['SYS_SKIN'] = Theme::get();

        // The following items should be refactored to no longer use $_SESSION
        // Since these items should be request scope specific and not session specific
        $sysCon["APPLICATION"]  = $_SESSION["APPLICATION"] ?? "";
        $sysCon["PROCESS"]      = $_SESSION["PROCESS"] ?? "";
        $sysCon["TASK"]         = $_SESSION["TASK"] ?? "";
        $sysCon["INDEX"]        = $_SESSION["INDEX"] ?? "";
        $sysCon['USER_LOGGED'] = Auth::user() ? Auth::user()->USR_UID : '';
        $sysCon['USER_USERNAME'] = Auth::user() ? Auth::user()->USR_USERNAME : '';

        return $sysCon;
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @note This extends the base Application to specify app instead of app as the main directory
     * @param  string  $path Optionally, a path to append to the app path
     * @return string
     */
    public function path($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function publicPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'public_html';
    }
}
