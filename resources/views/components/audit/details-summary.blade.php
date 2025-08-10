@props(['details' => null, 'maxLength' => 100])

@php
    // Parse the details JSON
    $parsedDetails = null;
    if (is_string($details)) {
        try {
            $parsedDetails = json_decode($details, true);
        } catch (Exception $e) {
            $parsedDetails = null;
        }
    } elseif (is_array($details)) {
        $parsedDetails = $details;
    }
    
    // Extract key components
    $requestContext = $parsedDetails['request_context'] ?? null;
    $message = $parsedDetails['message'] ?? null;
    
    // Parse user agent for device info
    $deviceIcon = 'computer-desktop';
    $deviceType = 'Desktop';
    $userAgentParser = null;
    
    if ($requestContext && isset($requestContext['user_agent'])) {
        $userAgentParser = new \App\Support\UserAgentParser($requestContext['user_agent']);
        $deviceIcon = $userAgentParser->getDeviceIcon();
        $deviceType = $userAgentParser->getDeviceType();
    }
    
    // Get summary text
    $summaryText = '';
    if ($message) {
        $summaryText = $message;
    } elseif ($requestContext && isset($requestContext['url'])) {
        $summaryText = $requestContext['method'] . ' ' . $requestContext['url'];
    } elseif (is_string($details)) {
        $summaryText = $details;
    } else {
        $summaryText = 'Audit log entry';
    }
    
    // Truncate if needed
    $displayText = Str::limit($summaryText, $maxLength);
    $hasMore = strlen($summaryText) > $maxLength;
@endphp

<div class="flex items-start gap-3">
    <div class="flex-1 min-w-0">
        <p class="text-sm text-text-primary {{ $hasMore ? 'truncate' : '' }}" 
           @if($hasMore) title="{{ $summaryText }}" @endif>
            {{ $displayText }}
        </p>
        
        @if($requestContext)
            <div class="flex items-center gap-3 mt-1">
                <!-- Device Type -->
                <div class="flex items-center gap-1">
                    <flux:icon name="{{ $deviceIcon }}" class="size-3 text-text-secondary" />
                    <span class="text-xs text-text-secondary">{{ $deviceType }}</span>
                </div>
                
                <!-- IP Address -->
                @if(isset($requestContext['ip_address']))
                    <div class="flex items-center gap-1">
                        <flux:icon name="globe-alt" class="size-3 text-text-secondary" />
                        <span class="text-xs text-text-secondary font-mono">{{ $requestContext['ip_address'] }}</span>
                    </div>
                @endif
                
                <!-- Browser Info (if not bot) -->
                @if($userAgentParser && !str_contains($deviceType, 'Bot'))
                    @php $browserInfo = $userAgentParser->getDeviceInfo(); @endphp
                    @if($browserInfo['browser'] !== 'Unknown')
                        <div class="flex items-center gap-1">
                            <span class="text-xs text-text-secondary">{{ $browserInfo['browser'] }}</span>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>
    
    @if($hasMore)
        <div class="flex-shrink-0">
            <flux:icon name="ellipsis-horizontal" class="size-4 text-text-secondary" title="Click to view full details" />
        </div>
    @endif
</div>