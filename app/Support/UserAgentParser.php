<?php

declare(strict_types=1);

namespace App\Support;

use Jenssegers\Agent\Agent;

/**
 * User Agent Parser utility class
 */
final class UserAgentParser
{
    private Agent $agent;
    
    public function __construct(?string $userAgent = null)
    {
        $this->agent = new Agent();
        if ($userAgent) {
            $this->agent->setUserAgent($userAgent);
        }
    }
    
    /**
     * Get parsed device information
     */
    public function getDeviceInfo(): array
    {
        return [
            'browser' => $this->agent->browser() ?: 'Unknown',
            'browser_version' => $this->agent->version($this->agent->browser()) ?: null,
            'platform' => $this->agent->platform() ?: 'Unknown',
            'platform_version' => $this->agent->version($this->agent->platform()) ?: null,
            'device' => $this->agent->device() ?: null,
            'is_mobile' => $this->agent->isMobile(),
            'is_tablet' => $this->agent->isTablet(),
            'is_desktop' => $this->agent->isDesktop(),
            'is_robot' => $this->agent->isRobot(),
        ];
    }
    
    /**
     * Get device type string
     */
    public function getDeviceType(): string
    {
        if ($this->agent->isRobot()) {
            return 'Robot/Bot';
        }
        
        if ($this->agent->isMobile()) {
            return 'Mobile';
        }
        
        if ($this->agent->isTablet()) {
            return 'Tablet';
        }
        
        return 'Desktop';
    }
    
    /**
     * Get device icon name for Flux UI
     */
    public function getDeviceIcon(): string
    {
        if ($this->agent->isRobot()) {
            return 'beaker';
        }
        
        if ($this->agent->isMobile()) {
            return 'device-phone-mobile';
        }
        
        if ($this->agent->isTablet()) {
            return 'device-tablet';
        }
        
        return 'computer-desktop';
    }
    
    /**
     * Get formatted browser string
     */
    public function getBrowserString(): string
    {
        $browser = $this->agent->browser();
        $version = $this->agent->version($browser);
        
        if (!$browser) {
            return 'Unknown Browser';
        }
        
        return $version ? "{$browser} {$version}" : $browser;
    }
    
    /**
     * Get formatted platform string
     */
    public function getPlatformString(): string
    {
        $platform = $this->agent->platform();
        $version = $this->agent->version($platform);
        
        if (!$platform) {
            return 'Unknown OS';
        }
        
        return $version ? "{$platform} {$version}" : $platform;
    }
    
    /**
     * Get comprehensive device summary
     */
    public function getDeviceSummary(): string
    {
        if ($this->agent->isRobot()) {
            $robot = $this->agent->robot();
            return $robot ?: 'Bot/Crawler';
        }
        
        $browser = $this->agent->browser();
        $platform = $this->agent->platform();
        
        if (!$browser && !$platform) {
            return 'Unknown Device';
        }
        
        if ($browser && $platform) {
            return "{$browser} on {$platform}";
        }
        
        return $browser ?: $platform;
    }
}