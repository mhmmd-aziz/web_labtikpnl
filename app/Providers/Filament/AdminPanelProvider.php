<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
// Pastikan semua use statements ini ada
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Filament\Navigation\NavigationBuilder; // <-- Namespace yang benar
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\PengajuanJadwalResource;
use App\Filament\Resources\RoomResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\JadwalResource;
use App\Filament\Resources\KelasResource; 
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\DosenResource;
use App\Filament\Resources\MahasiswaResource;
use App\Filament\Resources\ProdiResource;
use App\Filament\Resources\TimeSlotResource;
use App\Filament\Resources\MataKuliahResource;
use App\Filament\Resources\AngkatanResource;


// ------------------------------


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('LabTIK-PNL')
            ->brandLogo(asset('images/roomlight.png'))
            ->darkmodeBrandLogo(asset('images/roomdark.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/tik.png'))
            ->colors([
                'primary' => Color::hex('#9112BC'),
                'gray' => Color::Gray,
            ])
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->darkMode()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // --- BAGIAN NAVIGASI YANG DIPERBAIKI ---
           ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
    // Ambil role user yang login
    $userRole = Auth::check() ? Auth::user()->role : null;

    // --- Item Navigasi Umum ---
   $items = [
        NavigationItem::make('Dashboard')
            ->icon('heroicon-o-home')
            ->url(fn (): string => route('filament.admin.pages.dashboard'))
            ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.dashboard')),

        // Item Kustom untuk Pengajuan (visible to Dosen/Admin)
       // NavigationItem::make('Pengajuan Ganti Jadwal') // Nama di sidebar
            //->url(fn (): string => PengajuanJadwalResource::getUrl('index')) // URL tujuan tetap index
           // ->icon('heroicon-o-clipboard-document-list')
          //  ->visible(fn (): bool => $userRole === 'dosen' || $userRole === 'admin')
            // --- PERBAIKI isActiveWhen DENGAN routeIs ---
           // ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.resources.pengajuan-ganti-jadwal.*')),
            // ------------------------------------------
    ];

    // --- Grup Navigasi ---
    $groups = [];

    // Grup Manajemen Data (Hanya Admin, TANPA PengajuanJadwal)
    if ($userRole === 'admin') {
        $groups[] = NavigationGroup::make('Manajemen Data')
            ->items([
                ...(UserResource::getNavigationItems() ?? []),
                ...(RoomResource::getNavigationItems() ?? []),
                ...(JadwalResource::getNavigationItems() ?? []), // Admin lihat semua Jadwal
                ...(KelasResource::getNavigationItems() ?? []),
                ...(ProdiResource::getNavigationItems() ?? []),
                ...(MataKuliahResource::getNavigationItems() ?? []),
                
                // --- TAMBAHKAN DUA BARIS INI ---
                ...(DosenResource::getNavigationItems() ?? []),
                ...(MahasiswaResource::getNavigationItems() ?? []),
                ...(TimeSlotResource::getNavigationItems() ?? []),
                ...(AngkatanResource::getNavigationItems() ?? []),

                // ---------------------------------
                
                // --- HAPUS PengajuanJadwalResource DARI SINI ---
                // ...(PengajuanJadwalResource::getNavigationItems() ?? []),
                // ---------------------------------------------
            ]);
    }

    // Grup Jadwal Saya (Dosen & Mahasiswa)
    if ($userRole === 'dosen' || $userRole === 'mahasiswa') {
         if (class_exists(JadwalResource::class)) {
            $groups[] = NavigationGroup::make('Jadwal Saya')
                ->items([
                    // Tampilkan link ke JadwalResource (query difilter di Resource)
                    ...(JadwalResource::getNavigationItems() ?? []),
                ]);
         }
    }

    // Gabungkan items dan groups
    return $builder
        ->items($items) // Item level atas
        ->groups($groups); // Grup di bawahnya
});
            // --- AKHIR BAGIAN NAVIGASI ---
}

    // --- BAGIAN BOOT (Tidak berubah, pastikan role 'mahasiswa' benar) ---
    public function boot(): void
{
    // Hook untuk menyisipkan script notifikasi
    FilamentView::registerRenderHook(
        'panels::body.end',
        function(): string {
            // --- UBAH KONDISI DI SINI ---
            // Tampilkan jika user SUDAH LOGIN (role apa saja)
            if (Auth::check()) {
            // -----------------------------
                // Ambil VAPID key SEKALI saja
                $vapidPublicKey = env('VAPID_PUBLIC_KEY'); 
                // Jika VAPID key tidak ada, jangan render script
                if (empty($vapidPublicKey)) {
                    // Log::warning('VAPID_PUBLIC_KEY is not set in .env file.'); // Hapus atau biarkan untuk debug
                    return ''; 
                }

                return Blade::render(<<<HTML
                    <script>
                        // Langsung gunakan VAPID key
                        const VAPID_PUBLIC_KEY = '{$vapidPublicKey}'; 

                        // 2. Daftar Service Worker
                        if ('serviceWorker' in navigator && 'PushManager' in window) {
                            navigator.serviceWorker.register('/sw.js').then(swReg => {
                                console.log('Service Worker terdaftar.');

                                // 3. Cek status subscription saat halaman dimuat
                                swReg.pushManager.getSubscription().then(subscription => {
                                    const notifButton = document.getElementById('btn-notif-filament');
                                    if (subscription) {
                                        // User sudah subscribe, sembunyikan tombol
                                        if (notifButton) notifButton.style.display = 'none';
                                        console.log('User sudah subscribe.');
                                        // Opsional: Kirim subscription lagi untuk update jika perlu
                                        // sendSubscriptionToBackend(subscription);
                                    } else {
                                        // User belum subscribe, pasang event listener
                                        if (notifButton) {
                                            notifButton.style.display = 'inline-block'; // Pastikan tombol terlihat
                                            notifButton.addEventListener('click', () => {
                                                askForNotificationPermission(swReg);
                                            });
                                        } else {
                                            // Coba cari lagi setelah delay jika DOM belum siap
                                            setTimeout(() => {
                                                const delayedButton = document.getElementById('btn-notif-filament');
                                                if(delayedButton) {
                                                    delayedButton.style.display = 'inline-block';
                                                    delayedButton.addEventListener('click', () => {
                                                        askForNotificationPermission(swReg);
                                                    });
                                                } else {
                                                    console.warn('Tombol notifikasi #btn-notif-filament tidak ditemukan.');
                                                }
                                            }, 500);
                                        }
                                    }
                                });

                            }).catch(error => {
                                console.error('Pendaftaran Service Worker gagal:', error);
                            });
                        } else {
                            console.warn('Service Worker atau Push Manager tidak didukung browser ini.');
                        }

                        // 4. Fungsi Minta Izin
                        function askForNotificationPermission(swReg) {
                            const notifButton = document.getElementById('btn-notif-filament');
                            if (notifButton) notifButton.disabled = true; // Disable tombol saat proses

                            swReg.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
                            })
                            .then(subscription => {
                                console.log('User berhasil subscribe.');
                                sendSubscriptionToBackend(subscription);
                                // alert('Notifikasi berhasil diaktifkan!'); // Ganti alert
                                if (notifButton) notifButton.style.display = 'none'; // Sembunyikan tombol
                            })
                            .catch(err => {
                                console.error('Gagal subscribe:', err);
                                if (Notification.permission === 'denied') {
                                    alert('Anda telah memblokir notifikasi. Izinkan di pengaturan browser.');
                                    // Sembunyikan tombol jika diblokir permanen
                                    if (notifButton) notifButton.style.display = 'none';
                                } else {
                                    // alert('Gagal mengaktifkan notifikasi. Coba lagi.'); // Ganti alert
                                    if (notifButton) notifButton.disabled = false; // Aktifkan lagi jika error sementara
                                }
                            });
                        }

                        // 5. Fungsi Kirim 'alamat'
                        function sendSubscriptionToBackend(subscription) {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            if (!csrfToken) { console.error('CSRF token tidak ditemukan!'); return; }
                            fetch('{{ route('push.subscribe') }}', {
                                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                                body: JSON.stringify(subscription)
                            }).then(response => {
                                if (!response.ok) { console.error('Gagal mengirim subscription ke backend. Status:', response.status); }
                                else { console.log('Subscription berhasil dikirim ke backend.'); }
                            }).catch(error => { console.error('Error saat fetch subscription:', error); });
                        }

                        // 6. Fungsi Helper (WAJIB ADA)
                        function urlBase64ToUint8Array(base64String) {
                            const padding = '='.repeat((4 - base64String.length % 4) % 4);
                            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                            const rawData = window.atob(base64); const outputArray = new Uint8Array(rawData.length);
                            for (let i = 0; i < rawData.length; ++i) { outputArray[i] = rawData.charCodeAt(i); } return outputArray;
                        }
                    </script>
                HTML);
            }
            return ''; // Kembalikan string kosong jika belum login
        }
    );

    // Hook untuk menambahkan tombol notifikasi ke navbar
    FilamentView::registerRenderHook(
        'panels::global-search.before', // Atau hook lain yang cocok
        function(): string {
            // --- UBAH KONDISI DI SINI ---
            // Tampilkan jika user SUDAH LOGIN
            if (Auth::check()) {
            // -----------------------------
                // Kita tambahkan cek subscription di JavaScript saja agar lebih dinamis
                // Tombol akan disembunyikan oleh JS jika sudah subscribe
                return Blade::render('<div class="me-4"><button id="btn-notif-filament" style="display: none;" class="filament-button filament-button-size-md filament-button-color-primary px-3 py-1 text-sm font-semibold shadow">Aktifkan Notif</button></div>');
            }
            return ''; // Kembalikan string kosong jika belum login
        }
    );
}
    // --- AKHIR BAGIAN BOOT ---
}