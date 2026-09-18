<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📱 Input Resi Lapangan
        </h2>
    </x-slot>

    <div class="py-4 px-2 sm:px-6 lg:px-8 max-w-md mx-auto">
        
        <!-- Notification Alert -->
        @if (session('success'))
            <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl shadow-sm text-sm flex items-center gap-2" role="alert">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-xl shadow-sm text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl p-5 mb-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input AWB / Resi Baru
            </h3>

            <form action="{{ route('awb.store') }}" method="POST" class="space-y-4" id="awb-form">
                @csrf

                <!-- 1. Customer Name Input with Autocomplete Dropdown -->
                <div class="relative" id="customer-combobox-wrapper">
                    <label for="customer_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Nama Customer <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="customer_name" 
                           id="customer_name" 
                           value="{{ old('customer_name') }}"
                           required 
                           autocomplete="off"
                           placeholder="Ketik untuk mencari atau buat baru..." 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-base">

                    <!-- Autocomplete Suggestions Dropdown -->
                    <div id="customer-dropdown" 
                         class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-30 max-h-56 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                    </div>
                </div>

                <!-- 2. No Kontener (Autocomplete from API + Freeform) -->
                <div class="relative" id="container-combobox-wrapper">
                    <label for="no_container" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        No. Kontener
                    </label>
                    <input type="text" 
                           name="no_container" 
                           id="no_container" 
                           value="{{ old('no_container') }}"
                           required
                           autocomplete="off"
                           placeholder="Ketik No. Kontener (misal: TEGU 3000730)..." 
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-base font-mono uppercase">

                    <!-- Container Dropdown -->
                    <div id="container-dropdown" 
                         class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-30 max-h-56 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                    </div>
                </div>

                <!-- 3. AWB / Resi Input with Camera Scan Button -->
                <div>
                    <label for="awb" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Nomor Resi / AWB <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" 
                               name="awb" 
                               id="awb" 
                               value="{{ old('awb') }}"
                               required 
                               placeholder="Ketik atau Scan..." 
                               class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-base font-mono">
                        
                        <button type="button" 
                                id="btn-scan" 
                                onclick="openScanner()"
                                class="bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-medium px-4 py-3 rounded-xl flex items-center justify-center gap-1 shadow-sm transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-xs font-semibold">Scan</span>
                        </button>
                    </div>
                </div>

                <!-- 4. Jumlah Coli & Berat (KG) Grid -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="jumlah_coli" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Jumlah Coli
                        </label>
                        <input type="number" 
                               name="jumlah_coli" 
                               id="jumlah_coli" 
                               value="{{ old('jumlah_coli', 1) }}"
                               min="1" 
                               placeholder="1" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-base">
                    </div>
                    <div>
                        <label for="berat" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Berat (KG)
                        </label>
                        <input type="number" 
                               name="berat" 
                               id="berat" 
                               step="0.01"
                               min="0"
                               value="{{ old('berat') }}"
                               placeholder="0.00" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-base">
                    </div>
                </div>

                <!-- 5. Dimensi P x L x T (cm) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Dimensi P x L x T <span class="text-xs text-gray-400 font-normal">(cm)</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <input type="number" 
                               name="panjang" 
                               id="panjang" 
                               step="0.1"
                               min="0"
                               value="{{ old('panjang') }}"
                               placeholder="P (cm)" 
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm text-center">
                        
                        <input type="number" 
                               name="lebar" 
                               id="lebar" 
                               step="0.1"
                               min="0"
                               value="{{ old('lebar') }}"
                               placeholder="L (cm)" 
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm text-center">
                        
                        <input type="number" 
                               name="tinggi" 
                               id="tinggi" 
                               step="0.1"
                               min="0"
                               value="{{ old('tinggi') }}"
                               placeholder="T (cm)" 
                               class="w-full px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm text-center">
                    </div>
                </div>

                <!-- 6. Deskripsi (Opsional) -->
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Deskripsi / Keterangan <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" 
                           name="deskripsi" 
                           id="deskripsi" 
                           value="{{ old('deskripsi') }}"
                           placeholder="Contoh: Dus sepatu, Sepeda, etc." 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full mt-2 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold py-3.5 px-4 rounded-xl shadow-md flex items-center justify-center gap-2 text-base transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Data AWB
                </button>
            </form>
        </div>

        <!-- Recent Entries List -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Inputan Terakhir Saya</h4>
            
            @if($recentEntries->isEmpty())
                <p class="text-sm text-gray-400 text-center py-4">Belum ada data yang di-input hari ini.</p>
            @else
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($recentEntries as $entry)
                        <div class="py-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $entry->customer_name }}</p>
                                    <p class="text-xs font-mono text-blue-600 dark:text-blue-400 mt-0.5">Resi: {{ $entry->awb }}</p>
                                </div>
                                <span class="text-[11px] text-gray-400 flex-shrink-0">
                                    {{ $entry->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            @if($entry->no_container || $entry->jumlah_coli || $entry->berat || $entry->panjang)
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex flex-wrap gap-x-3 gap-y-1 bg-gray-50 dark:bg-gray-900/50 p-2 rounded-lg font-mono">
                                    @if($entry->no_container)
                                        <span>📦 Container: <strong class="text-gray-700 dark:text-gray-300">{{ $entry->no_container }}</strong></span>
                                    @endif
                                    @if($entry->jumlah_coli)
                                        <span>Coli: <strong>{{ $entry->jumlah_coli }}</strong></span>
                                    @endif
                                    @if($entry->berat)
                                        <span>Berat: <strong>{{ $entry->berat }} kg</strong></span>
                                    @endif
                                    @if($entry->panjang && $entry->lebar && $entry->tinggi)
                                        <span>Dimensi: <strong>{{ (float)$entry->panjang }}x{{ (float)$entry->lebar }}x{{ (float)$entry->tinggi }} cm</strong></span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Scanner Modal Overlay -->
    <div id="scanner-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex flex-col justify-center items-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-sm w-full p-4 text-center shadow-2xl relative">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-gray-900 dark:text-white text-base">Scan QR / Barcode Resi</h4>
                <button type="button" onclick="closeScanner()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Camera Viewfinder Box -->
            <div id="reader" class="w-full rounded-xl overflow-hidden bg-gray-900 min-h-[260px]"></div>

            <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Arahkan kamera ke QR Code atau Barcode pada paket/resi</p>

            <button type="button" onclick="closeScanner()" class="mt-4 w-full py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold rounded-xl text-sm">
                Tutup Kamera
            </button>
        </div>
    </div>

    <!-- HTML5 QRCode Scanner Script -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrcodeScanner = null;

        function openScanner() {
            document.getElementById('scanner-modal').classList.remove('hidden');
            
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }

            const config = { 
                fps: 15, 
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.0
            };

            html5QrcodeScanner.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                alert("Gagal mengakses kamera: " + err + "\n\nPastikan izin kamera diaktifkan dan web dibuka via HTTPS.");
                closeScanner();
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('awb').value = decodedText;
            
            if (navigator.vibrate) {
                navigator.vibrate(100);
            }
            
            closeScanner();
        }

        function onScanFailure(error) {
            // Ignore scan attempt failures
        }

        function closeScanner() {
            if (html5QrcodeScanner && html5QrcodeScanner.isScanning) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('scanner-modal').classList.add('hidden');
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                    document.getElementById('scanner-modal').classList.add('hidden');
                });
            } else {
                document.getElementById('scanner-modal').classList.add('hidden');
            }
        }

        // --- Generic Autocomplete Helper ---
        function initAutocomplete(inputId, dropdownId, wrapperId, fetchUrl) {
            const inputEl = document.getElementById(inputId);
            const dropdownEl = document.getElementById(dropdownId);
            const wrapperEl = document.getElementById(wrapperId);
            let timer = null;

            if (!inputEl || !dropdownEl || !wrapperEl) return;

            inputEl.addEventListener('input', function () {
                clearTimeout(timer);
                const query = this.value.trim();

                if (query.length === 0) {
                    dropdownEl.classList.add('hidden');
                    dropdownEl.innerHTML = '';
                    return;
                }

                timer = setTimeout(() => {
                    fetch(`${fetchUrl}?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(res => {
                            if (res.status === 'success' && Array.isArray(res.data) && res.data.length > 0) {
                                renderDropdown(dropdownEl, inputEl, res.data, query);
                            } else {
                                renderEmpty(dropdownEl, query);
                            }
                        })
                        .catch(err => {
                            console.error(`Fetch error for ${inputId}:`, err);
                            dropdownEl.classList.add('hidden');
                        });
                }, 300);
            });

            document.addEventListener('click', function (e) {
                if (!wrapperEl.contains(e.target)) {
                    dropdownEl.classList.add('hidden');
                }
            });
        }

        function renderDropdown(dropdownEl, inputEl, items, query) {
            dropdownEl.innerHTML = '';
            
            items.forEach(val => {
                const itemEl = document.createElement('div');
                itemEl.className = 'px-4 py-3 text-sm text-gray-800 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer transition flex items-center justify-between font-medium';
                
                const regEx = new RegExp(`(${escapeRegExp(query)})`, 'gi');
                const highlighted = val.replace(regEx, '<span class="bg-blue-500/20 text-blue-600 dark:text-blue-400 font-bold px-1 py-0.5 rounded">$1</span>');
                
                itemEl.innerHTML = `
                    <span>${highlighted}</span>
                    <span class="text-[11px] text-gray-400 bg-gray-100 dark:bg-gray-900 px-2 py-0.5 rounded-full">Pilih</span>
                `;

                itemEl.addEventListener('click', () => {
                    inputEl.value = val;
                    dropdownEl.classList.add('hidden');
                });

                dropdownEl.appendChild(itemEl);
            });

            dropdownEl.classList.remove('hidden');
        }

        function renderEmpty(dropdownEl, query) {
            dropdownEl.innerHTML = `
                <div class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Gunakan data baru <strong>"${escapeHtml(query)}"</strong></span>
                </div>
            `;
            dropdownEl.classList.remove('hidden');
        }

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function escapeHtml(string) {
            return string.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // Initialize Customer and Container Autocompletes
        document.addEventListener('DOMContentLoaded', function () {
            initAutocomplete('customer_name', 'customer-dropdown', 'customer-combobox-wrapper', '/customers-search');
            initAutocomplete('no_container', 'container-dropdown', 'container-combobox-wrapper', '/containers-search');
        });
    </script>
</x-app-layout>
