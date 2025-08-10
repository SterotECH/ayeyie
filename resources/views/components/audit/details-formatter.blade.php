@props(['details' => null, 'compact' => false])

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
    $logLevel = $parsedDetails['log_level'] ?? null;
    $timestamp = $parsedDetails['timestamp'] ?? null;
    $message = $parsedDetails['message'] ?? null;
    
    // Parse user agent if available
    $userAgent = null;
    $deviceInfo = null;
    $userAgentParser = null;
    if ($requestContext && isset($requestContext['user_agent'])) {
        $userAgent = $requestContext['user_agent'];
        $userAgentParser = new \App\Support\UserAgentParser($userAgent);
        $deviceInfo = $userAgentParser->getDeviceInfo();
    }
    
    // Remove processed keys from parsedDetails for the remaining data
    $otherDetails = $parsedDetails;
    if ($otherDetails) {
        unset($otherDetails['request_context'], $otherDetails['log_level'], $otherDetails['timestamp'], $otherDetails['message']);
    }
@endphp

@if($parsedDetails)
    <div class="space-y-4">
        @if($message && !$compact)
            <!-- Message -->
            <div class="bg-info/5 border border-info/20 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <flux:icon name="information-circle" class="size-4 text-info flex-shrink-0 mt-0.5" />
                    <p class="text-sm text-text-primary">{{ $message }}</p>
                </div>
            </div>
        @endif

        @if($requestContext)
            <!-- Request Context -->
            <div class="bg-card border border-border rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-border bg-muted/30">
                    <div class="flex items-center gap-2">
                        <flux:icon name="globe-alt" class="size-4 text-text-secondary" />
                        <h4 class="text-sm font-semibold text-text-primary">Request Information</h4>
                    </div>
                </div>
                <div class="p-4">
                    <dl class="grid grid-cols-1 {{ $compact ? 'gap-2' : 'gap-4 sm:grid-cols-2' }}">
                        <!-- IP Address -->
                        @if(isset($requestContext['ip_address']))
                            <div>
                                <dt class="text-xs font-medium text-text-secondary uppercase tracking-wide">IP Address</dt>
                                <dd class="mt-1 flex items-center gap-2">
                                    <code class="text-sm font-mono bg-muted/50 px-2 py-1 rounded text-text-primary">{{ $requestContext['ip_address'] }}</code>
                                </dd>
                            </div>
                        @endif

                        <!-- Request Method & URL -->
                        @if(isset($requestContext['method']) || isset($requestContext['url']))
                            <div class="{{ $compact ? '' : 'sm:col-span-2' }}">
                                <dt class="text-xs font-medium text-text-secondary uppercase tracking-wide">Request</dt>
                                <dd class="mt-1 flex items-center gap-2 flex-wrap">
                                    @if(isset($requestContext['method']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ 
                                            $requestContext['method'] === 'GET' ? 'bg-success/10 text-success' : 
                                            ($requestContext['method'] === 'POST' ? 'bg-primary/10 text-primary' : 
                                            ($requestContext['method'] === 'PUT' ? 'bg-warning/10 text-warning' : 
                                            ($requestContext['method'] === 'DELETE' ? 'bg-error/10 text-error' : 'bg-muted text-text-secondary'))) 
                                        }}">
                                            {{ $requestContext['method'] }}
                                        </span>
                                    @endif
                                    @if(isset($requestContext['url']) && !$compact)
                                        <code class="text-sm font-mono bg-muted/50 px-2 py-1 rounded text-text-primary break-all">{{ $requestContext['url'] }}</code>
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </dl>

                    @if($deviceInfo && !$compact)
                        <!-- Device Information -->
                        <div class="mt-4 pt-4 border-t border-border">
                            <h5 class="text-sm font-semibold text-text-primary mb-3 flex items-center gap-2">
                                <flux:icon name="{{ $userAgentParser->getDeviceIcon() }}" class="size-4" />
                                Device Information
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @if($deviceInfo['browser'] !== 'Unknown')
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-text-secondary">Browser:</span>
                                        <span class="text-sm font-medium text-text-primary">{{ $userAgentParser->getBrowserString() }}</span>
                                    </div>
                                @endif
                                
                                @if($deviceInfo['platform'] !== 'Unknown')
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-text-secondary">OS:</span>
                                        <span class="text-sm font-medium text-text-primary">{{ $userAgentParser->getPlatformString() }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-text-secondary">Device:</span>
                                    <div class="flex items-center gap-1">
                                        @if($deviceInfo['is_robot'])
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning">
                                                <flux:icon name="beaker" class="size-3 mr-1" />
                                                {{ $userAgentParser->getDeviceType() }}
                                            </span>
                                        @elseif($deviceInfo['is_mobile'])
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-info/10 text-info">
                                                <flux:icon name="device-phone-mobile" class="size-3 mr-1" />
                                                Mobile
                                            </span>
                                        @elseif($deviceInfo['is_tablet'])
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-secondary/10 text-secondary">
                                                <flux:icon name="device-tablet" class="size-3 mr-1" />
                                                Tablet
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                                                <flux:icon name="computer-desktop" class="size-3 mr-1" />
                                                Desktop
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($deviceInfo && $compact)
                        <!-- Compact Device Info -->
                        <div class="mt-3 pt-3 border-t border-border">
                            <div class="flex items-center gap-2 text-sm">
                                <flux:icon name="{{ $userAgentParser->getDeviceIcon() }}" class="size-4 text-text-secondary" />
                                <span class="text-text-primary">{{ $userAgentParser->getDeviceSummary() }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($otherDetails && count($otherDetails) > 0)
            <!-- Additional Details -->
            <div class="bg-card border border-border rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-border bg-muted/30">
                    <div class="flex items-center gap-2">
                        <flux:icon name="document-text" class="size-4 text-text-secondary" />
                        <h4 class="text-sm font-semibold text-text-primary">Additional Details</h4>
                    </div>
                </div>
                <div class="p-4">
                    <div class="bg-muted/30 rounded-lg p-4 border border-border/50">
                        <pre class="text-sm text-text-primary whitespace-pre-wrap overflow-x-auto"><code>{{ json_encode($otherDetails, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                </div>
            </div>
        @endif
    </div>
@elseif($details)
    <!-- Fallback for non-JSON details -->
    <div class="bg-muted/30 rounded-lg p-4 border border-border/50">
        <pre class="text-sm text-text-primary whitespace-pre-wrap overflow-x-auto"><code>{{ is_string($details) ? $details : json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
    </div>
@else
    <!-- No Details Available -->
    <div class="text-center py-4">
        <flux:icon name="document" class="size-8 text-text-secondary mx-auto mb-2" />
        <p class="text-sm text-text-secondary">No additional details available</p>
    </div>
@endif