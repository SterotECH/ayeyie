<div>
    <x-ui.admin-page-layout
        title="{{ $mode === 'payment' ? 'Payment Processing' : 'Pickup Verification' }} - QR Scanner"
        description="Scan customer QR codes for {{ $mode === 'payment' ? 'payment processing' : 'pickup authentication' }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => $mode === 'payment' ? 'Payment Processing' : 'Pickup Verification']
        ]"
    >
        <div class="max-w-4xl mx-auto space-y-6">
            
            @if(!$orderFound)
                {{-- QR Scanner Interface --}}
                <div class="bg-card rounded-lg border border-border p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="size-12 rounded-lg bg-primary/10 flex items-center justify-center">
                            <flux:icon name="qr-code" class="size-6 text-primary" />
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-text-primary">
                                {{ $mode === 'payment' ? 'Scan for Payment Processing' : 'Scan for Pickup Verification' }}
                            </h3>
                            <p class="text-sm text-text-secondary">
                                {{ $mode === 'payment' 
                                    ? 'Scan the customer\'s QR code to process their cash on delivery payment' 
                                    : 'Scan the customer\'s QR code to authenticate and complete their order pickup' }}
                            </p>
                        </div>
                    </div>

                    {{-- Mode Information --}}
                    <div class="mb-6 p-4 rounded-lg {{ $mode === 'payment' ? 'bg-warning/10 border border-warning/20' : 'bg-success/10 border border-success/20' }}">
                        <div class="flex items-start gap-3">
                            <flux:icon name="{{ $mode === 'payment' ? 'currency-dollar' : 'shield-check' }}" 
                                class="size-5 {{ $mode === 'payment' ? 'text-warning' : 'text-success' }} flex-shrink-0 mt-0.5" />
                            <div>
                                <h4 class="text-sm font-semibold {{ $mode === 'payment' ? 'text-warning' : 'text-success' }}">
                                    {{ $mode === 'payment' ? 'Payment Processing Mode' : 'Pickup Verification Mode' }}
                                </h4>
                                <p class="text-sm text-text-secondary mt-1">
                                    @if($mode === 'payment')
                                        This scanner will process cash on delivery payments. After scanning, you'll be directed to collect payment from the customer.
                                    @else
                                        This scanner will authenticate customer pickups. Only orders with completed payments can be picked up.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Camera Scanner --}}
                    <div class="space-y-4">
                        <div class="relative">
                            {{-- Camera Preview --}}
                            <div id="camera-container" class="relative bg-muted/20 rounded-lg border-2 border-dashed border-border overflow-hidden" style="height: 400px;">
                                <video id="camera-preview" class="w-full h-full object-cover hidden" autoplay playsinline></video>
                                
                                {{-- Scanner Overlay --}}
                                <div id="scanner-overlay" class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <flux:icon name="qr-code" class="size-16 text-text-secondary mx-auto mb-4" />
                                        <h4 class="text-lg font-medium text-text-primary mb-2">Ready to Scan</h4>
                                        <p class="text-sm text-text-secondary mb-4">Click "Start Camera" to begin scanning QR codes</p>
                                        <flux:button id="start-camera-btn" variant="primary" icon="camera">
                                            Start Camera
                                        </flux:button>
                                    </div>
                                </div>

                                {{-- Scanning Frame --}}
                                <div id="scanning-frame" class="absolute inset-0 hidden">
                                    <div class="absolute inset-0 bg-black/50"></div>
                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                        <div class="w-64 h-64 border-4 border-primary rounded-lg relative">
                                            <div class="absolute -top-1 -left-1 w-8 h-8 border-t-4 border-l-4 border-primary"></div>
                                            <div class="absolute -top-1 -right-1 w-8 h-8 border-t-4 border-r-4 border-primary"></div>
                                            <div class="absolute -bottom-1 -left-1 w-8 h-8 border-b-4 border-l-4 border-primary"></div>
                                            <div class="absolute -bottom-1 -right-1 w-8 h-8 border-b-4 border-r-4 border-primary"></div>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2">
                                        <div class="bg-black/75 text-white px-4 py-2 rounded-lg text-sm">
                                            Position QR code within the frame
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Camera Controls --}}
                            <div id="camera-controls" class="hidden mt-4 flex justify-center gap-3">
                                <flux:button id="stop-camera-btn" variant="outline" icon="x-mark">
                                    Stop Camera
                                </flux:button>
                                <flux:button id="switch-camera-btn" variant="outline" icon="arrow-path" style="display: none;">
                                    Switch Camera
                                </flux:button>
                            </div>
                        </div>

                        {{-- Manual Entry Fallback --}}
                        <div class="border-t border-border pt-6">
                            <h4 class="text-sm font-medium text-text-primary mb-4">Manual Entry (Fallback)</h4>
                            <form wire:submit.prevent="processScannedData" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <flux:field>
                                        <flux:label>Receipt Code</flux:label>
                                        <flux:input 
                                            wire:model="receiptCode" 
                                            placeholder="e.g., AYE2508101234" 
                                            class="font-mono"
                                        />
                                        <flux:error name="receiptCode"/>
                                    </flux:field>

                                    <flux:field>
                                        <flux:label>Transaction ID (Optional)</flux:label>
                                        <flux:input 
                                            type="number" 
                                            wire:model="transactionId" 
                                            placeholder="e.g., 123"
                                        />
                                        <flux:error name="transactionId"/>
                                    </flux:field>
                                </div>

                                <div class="flex gap-3">
                                    <flux:button type="submit" variant="primary" icon="magnifying-glass">
                                        Process Code
                                    </flux:button>
                                    @if($receiptCode || $transactionId)
                                        <flux:button type="button" wire:click="resetScanner" variant="ghost">
                                            Clear
                                        </flux:button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                {{-- Order Found - Show Details --}}
                <div class="bg-card rounded-lg border border-border overflow-hidden">
                    <div class="px-6 py-4 border-b border-border bg-success/5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <flux:icon name="check-circle" class="size-6 text-success" />
                                <div>
                                    <h3 class="text-lg font-semibold text-text-primary">
                                        {{ $mode === 'payment' ? 'Order Ready for Payment' : 'Order Ready for Pickup' }}
                                    </h3>
                                    <p class="text-sm text-text-secondary">{{ $receiptCode }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-text-primary">₵{{ number_format($transaction->total_amount, 2) }}</div>
                                <div class="text-sm text-text-secondary">Total Amount</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Customer Info --}}
                            <div>
                                <h4 class="text-sm font-semibold text-text-primary mb-3 flex items-center gap-2">
                                    <flux:icon name="user" class="size-4" />
                                    Customer Information
                                </h4>
                                <div class="bg-muted/30 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-text-secondary">Name:</span>
                                        <span class="font-medium text-text-primary">{{ $customerName }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-text-secondary">Phone:</span>
                                        <span class="font-medium text-text-primary">{{ $customerPhone }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Order Info --}}
                            <div>
                                <h4 class="text-sm font-semibold text-text-primary mb-3 flex items-center gap-2">
                                    <flux:icon name="document-text" class="size-4" />
                                    Order Details
                                </h4>
                                <div class="bg-muted/30 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-text-secondary">Order ID:</span>
                                        <span class="font-medium text-text-primary">#{{ $transaction->transaction_id }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-text-secondary">Order Date:</span>
                                        <span class="font-medium text-text-primary">{{ $transaction->transaction_date->format('M j, Y g:i A') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-text-secondary">Items:</span>
                                        <span class="font-medium text-text-primary">{{ $transaction->items->count() }} item(s)</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-text-secondary">Payment Status:</span>
                                        <span class="font-medium {{ $transaction->payment_status === 'completed' ? 'text-success' : 'text-warning' }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex justify-between items-center pt-4 border-t border-border">
                            <div>
                                <h4 class="font-medium text-text-primary">Ready to Proceed</h4>
                                <p class="text-sm text-text-secondary mt-1">
                                    {{ $mode === 'payment' ? 'Process the cash payment for this order' : 'Complete the pickup verification for this order' }}
                                </p>
                                <div id="auto-redirect-message" class="hidden mt-2 text-sm text-primary">
                                    <span class="inline-flex items-center gap-2">
                                        <flux:icon name="clock" class="size-4" />
                                        Redirecting in <span id="countdown-timer" class="font-semibold">3</span> seconds...
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <flux:button wire:click="resetScanner" variant="outline">
                                    Scan Another
                                </flux:button>
                                <flux:button wire:click="proceedToAction" variant="primary" 
                                    icon="{{ $mode === 'payment' ? 'currency-dollar' : 'truck' }}">
                                    {{ $mode === 'payment' ? 'Process Payment' : 'Verify Pickup' }}
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Mode Switcher --}}
            <div class="bg-card rounded-lg border border-border p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-text-primary">Switch Scanner Mode</h4>
                        <p class="text-xs text-text-secondary">Currently in {{ $mode === 'payment' ? 'payment processing' : 'pickup verification' }} mode</p>
                    </div>
                    <div class="flex gap-2">
                        <flux:button 
                            href="{{ route('staff.qr-scanner', ['mode' => 'payment']) }}" 
                            variant="{{ $mode === 'payment' ? 'primary' : 'outline' }}"
                            size="sm"
                            icon="currency-dollar"
                            wire:navigate
                        >
                            Payment Mode
                        </flux:button>
                        <flux:button 
                            href="{{ route('staff.qr-scanner', ['mode' => 'pickup']) }}" 
                            variant="{{ $mode === 'pickup' ? 'primary' : 'outline' }}"
                            size="sm"
                            icon="truck"
                            wire:navigate
                        >
                            Pickup Mode
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.admin-page-layout>

    {{-- QR Scanner JavaScript --}}
    @push('scripts')
    @vite('resources/js/qr-scanner.js')
    <script>
        let video = null;
        let canvas = null;
        let context = null;
        let currentStream = null;
        let cameras = [];
        let currentCameraIndex = 0;
        let isScanning = false;
        let scanInterval = null;

        document.addEventListener('DOMContentLoaded', async function() {
            await initializeScanner();
        });

        async function initializeScanner() {
            try {
                console.log('Initializing QR Scanner...');
                
                // Check if jsQR is available
                if (typeof window.jsQR === 'undefined') {
                    console.error('jsQR library not loaded');
                    showError('QR Scanner library not loaded. Please refresh the page.');
                    return;
                }

                // Check if getUserMedia is supported
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    console.error('getUserMedia not supported');
                    showError('Camera access not supported in this browser.');
                    return;
                }

                video = document.getElementById('camera-preview');
                if (!video) {
                    console.error('Video element not found');
                    return;
                }
                
                // Create hidden canvas for QR processing
                canvas = document.createElement('canvas');
                context = canvas.getContext('2d');

                // Get available cameras
                await getCameras();

                // Add event listeners
                const startBtn = document.getElementById('start-camera-btn');
                const stopBtn = document.getElementById('stop-camera-btn');
                const switchBtn = document.getElementById('switch-camera-btn');

                if (startBtn) startBtn.addEventListener('click', startCamera);
                if (stopBtn) stopBtn.addEventListener('click', stopCamera);
                if (switchBtn) switchBtn.addEventListener('click', switchCamera);

                console.log('QR Scanner initialized successfully with jsQR library');

            } catch (err) {
                console.error('Failed to initialize scanner:', err);
                showError('Failed to initialize camera scanner: ' + err.message);
            }
        }

        async function getCameras() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                cameras = devices.filter(device => device.kind === 'videoinput');
                
                console.log('Found cameras:', cameras.length);
                
                if (cameras.length > 1) {
                    const switchBtn = document.getElementById('switch-camera-btn');
                    if (switchBtn) {
                        switchBtn.style.display = 'block';
                    }
                }
            } catch (err) {
                console.warn('Could not enumerate cameras:', err);
            }
        }

        async function startCamera() {
            console.log('startCamera() called, isScanning:', isScanning);
            
            if (isScanning) {
                console.log('Already scanning, returning');
                return;
            }

            const overlay = document.getElementById('scanner-overlay');
            const frame = document.getElementById('scanning-frame');
            const controls = document.getElementById('camera-controls');
            const startBtn = document.getElementById('start-camera-btn');

            try {
                console.log('Disabling start button and preparing camera...');
                
                // Disable start button
                if (startBtn) startBtn.disabled = true;

                // Configure camera constraints
                const constraints = {
                    video: {
                        facingMode: 'environment', // Try to use back camera first
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                };

                // Use specific camera if available
                if (cameras.length > 0 && cameras[currentCameraIndex]) {
                    console.log('Using specific camera:', cameras[currentCameraIndex].label);
                    constraints.video.deviceId = { exact: cameras[currentCameraIndex].deviceId };
                } else {
                    console.log('No specific camera selected, using default');
                }

                console.log('Starting camera with constraints:', constraints);

                // Get camera stream
                console.log('Requesting camera access...');
                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                console.log('Camera stream obtained:', currentStream);
                
                video.srcObject = currentStream;
                console.log('Video srcObject set');
                
                // Wait for video to be ready
                console.log('Waiting for video metadata...');
                await new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => {
                        reject(new Error('Video metadata timeout'));
                    }, 10000);
                    
                    video.onloadedmetadata = () => {
                        clearTimeout(timeout);
                        console.log('Video metadata loaded, starting playback...');
                        video.play().then(() => {
                            console.log('Video playing, dimensions:', video.videoWidth, 'x', video.videoHeight);
                            resolve();
                        }).catch(reject);
                    };
                    
                    video.onerror = (e) => {
                        clearTimeout(timeout);
                        reject(new Error('Video error: ' + e.message));
                    };
                });

                // Set canvas size to match video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                console.log('Canvas size set to:', canvas.width, 'x', canvas.height);

                // Start scanning
                isScanning = true;
                startScanning();

                // Update UI
                video.classList.remove('hidden');
                if (overlay) overlay.classList.add('hidden');
                if (frame) frame.classList.remove('hidden');
                if (controls) controls.classList.remove('hidden');

                console.log('Camera started successfully, UI updated');

            } catch (err) {
                console.error('Failed to start camera:', err);
                isScanning = false;
                
                if (startBtn) startBtn.disabled = false;
                
                let errorMsg = 'Could not access camera. ';
                if (err.name === 'NotAllowedError') {
                    errorMsg += 'Please allow camera permissions and try again.';
                    console.log('Camera permission denied');
                } else if (err.name === 'NotFoundError') {
                    errorMsg += 'No camera found on this device.';
                    console.log('No camera found');
                } else if (err.name === 'OverconstrainedError') {
                    errorMsg += 'Camera constraints not supported. Trying alternative...';
                    console.log('Camera constraints not supported, trying basic');
                    // Try with basic constraints
                    setTimeout(() => startCameraBasic(), 500);
                    return;
                } else if (err.message === 'Video metadata timeout') {
                    errorMsg += 'Camera took too long to initialize. Please try again.';
                    console.log('Video metadata timeout');
                } else {
                    errorMsg += 'Please check camera permissions and try again. (' + err.message + ')';
                    console.log('Other camera error:', err);
                }
                
                showError(errorMsg);
            }
        }

        async function startCameraBasic() {
            try {
                const constraints = { video: true };
                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = currentStream;
                
                await new Promise((resolve) => {
                    video.onloadedmetadata = () => {
                        video.play();
                        resolve();
                    };
                });

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                isScanning = true;
                startScanning();

                // Update UI
                video.classList.remove('hidden');
                document.getElementById('scanner-overlay').classList.add('hidden');
                document.getElementById('scanning-frame').classList.remove('hidden');
                document.getElementById('camera-controls').classList.remove('hidden');

            } catch (err) {
                console.error('Failed to start basic camera:', err);
                showError('Unable to access camera. Please check permissions.');
                document.getElementById('start-camera-btn').disabled = false;
            }
        }

        function startScanning() {
            scanInterval = setInterval(() => {
                if (!isScanning || !video.videoWidth || !video.videoHeight) {
                    return;
                }

                // Draw video frame to canvas
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Get image data
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                
                // Scan for QR code
                const qrCode = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert'
                });

                if (qrCode) {
                    console.log('QR Code detected:', qrCode.data);
                    
                    // Process the QR code with Livewire
                    @this.call('processQrData', qrCode.data);
                    
                    // Stop scanning after successful read
                    stopCamera();
                }
            }, 250); // Scan every 250ms
        }

        function stopCamera() {
            console.log('Stopping camera');
            isScanning = false;

            // Clear scan interval
            if (scanInterval) {
                clearInterval(scanInterval);
                scanInterval = null;
            }

            // Stop video stream
            if (currentStream) {
                currentStream.getTracks().forEach(track => {
                    track.stop();
                });
                currentStream = null;
            }

            // Reset UI
            const overlay = document.getElementById('scanner-overlay');
            const frame = document.getElementById('scanning-frame');
            const controls = document.getElementById('camera-controls');
            const startBtn = document.getElementById('start-camera-btn');

            if (video) {
                video.classList.add('hidden');
                video.srcObject = null;
            }
            if (overlay) overlay.classList.remove('hidden');
            if (frame) frame.classList.add('hidden');
            if (controls) controls.classList.add('hidden');
            if (startBtn) startBtn.disabled = false;
        }

        function switchCamera() {
            if (cameras.length > 1) {
                currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
                console.log('Switching to camera:', currentCameraIndex, cameras[currentCameraIndex].label);
                
                // Restart camera with new device
                stopCamera();
                setTimeout(startCamera, 500);
            }
        }

        function showError(message) {
            alert(message);
            console.error('QR Scanner Error:', message);
        }

        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            stopCamera();
        });

        // Handle Livewire navigation cleanup
        document.addEventListener('livewire:navigating', function() {
            stopCamera();
        });

        // Handle auto-redirect after successful QR scan
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('auto-redirect-after-delay', function() {
                console.log('Auto-redirect triggered, redirecting in 3 seconds...');
                
                // Show countdown message
                const redirectMessage = document.getElementById('auto-redirect-message');
                const countdownTimer = document.getElementById('countdown-timer');
                
                if (redirectMessage) {
                    redirectMessage.classList.remove('hidden');
                }
                
                // Show countdown
                let countdown = 3;
                const countdownInterval = setInterval(() => {
                    console.log('Redirecting in', countdown, 'seconds...');
                    
                    if (countdownTimer) {
                        countdownTimer.textContent = countdown;
                    }
                    
                    countdown--;
                    
                    if (countdown < 0) {
                        clearInterval(countdownInterval);
                        console.log('Triggering redirect...');
                        @this.call('proceedToAction');
                    }
                }, 1000);
            });
        });
    </script>
    @endpush
</div>