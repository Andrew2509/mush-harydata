<footer class="relative z-10 w-full bg-slate-900 border-t border-slate-800 pt-16 pb-8 mt-auto overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 to-slate-900 opacity-90 pointer-events-none"></div>
    <!-- Background Glows -->
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-secondary/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-12">
            <div class="lg:col-span-4 flex flex-col items-center text-center gap-6">
                <div class="relative w-24 h-24 group">
                    <div
                        class="absolute inset-0 bg-orange-500/10 rounded-full blur-lg group-hover:bg-orange-500/20 transition-all duration-500">
                    </div>
                    <div class="absolute inset-0 bg-primary/5 rounded-full blur-md animate-pulse"></div>
                    <div class="relative z-10 w-full h-full flex flex-col items-center justify-center">
                        <?php if($config->logo_footer): ?>
                            <img src="<?php echo e(url('')); ?><?php echo e($config->logo_footer); ?>" alt="Logo Footer"
                                class="w-16 h-16 object-contain drop-shadow-[0_0_8px_rgba(249,115,22,0.4)] hover:scale-110 transition-transform duration-300 filter brightness-110 contrast-125">
                        <?php else: ?>
                            <div
                                class="w-12 h-12 bg-primary flex items-center justify-center rounded-xl shadow-lg shadow-primary/20">
                                <span
                                    class="text-background-dark font-black text-2xl uppercase"><?php echo e(substr(ENV('APP_NAME'), 0, 1)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="text-slate-400 text-sm leading-7 text-justify font-body font-medium">
                    <?php echo nl2br(e($config->deskripsi_web)); ?>

                </p>
            </div>

            <div class="lg:col-span-2 lg:col-start-6">
                <h3 class="text-white font-display font-bold text-lg mb-6 relative inline-block uppercase tracking-widest">
                    KEMITRAAN
                    <span class="absolute -bottom-2 left-0 w-1/2 h-0.5 bg-primary"></span>
                </h3>
                <ul class="space-y-4 text-sm text-slate-400 font-bold">
                    <li>
                        <a class="hover:text-primary hover:pl-2 transition-all duration-200 flex items-center gap-2"
                            href="#">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            Jasa Pembuatan Web Topup
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary hover:pl-2 transition-all duration-200 flex items-center gap-2"
                            href="#">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            Hosting Dan Domain
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary hover:pl-2 transition-all duration-200 flex items-center gap-2"
                            href="#">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            API Integration
                        </a>
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-white font-display font-bold text-lg mb-6 relative inline-block uppercase tracking-widest">
                    PETA SITUS
                    <span class="absolute -bottom-2 left-0 w-1/2 h-0.5 bg-primary"></span>
                </h3>
                <ul class="space-y-4 text-sm text-slate-400 font-bold">
                    <li>
                        <a class="hover:text-primary hover:pl-2 transition-all duration-200 flex items-center gap-2"
                            href="<?php echo e(url('/')); ?>">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary hover:pl-2 transition-all duration-200 flex items-center gap-2"
                            href="<?php echo e(route('cari')); ?>">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            Cek Transaksi
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary hover:pl-2 transition-all duration-200 flex items-center gap-2"
                            href="#">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            Hubungi Kami
                        </a>
                    </li>
                    <li>
                        <a class="hover:text-primary hover:pl-2 transition-all duration-200 flex items-center gap-2"
                            href="#">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            Ulasan
                        </a>
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h3 class="text-white font-display font-bold text-lg mb-6 relative inline-block uppercase tracking-widest">
                    SOSIAL MEDIA
                    <span class="absolute -bottom-2 left-0 w-1/2 h-0.5 bg-primary"></span>
                </h3>
                <p class="text-slate-400 text-sm mb-6 font-body font-medium">Ikuti kami untuk update promo terbaru dan info maintenance.</p>
                <div class="flex gap-4">
                    <a class="w-12 h-12 rounded-full glass-icon flex items-center justify-center group hover:bg-[#25D366] transition-all duration-300 border border-slate-700 hover:border-[#25D366]"
                        href="<?php echo e($config->url_wa); ?>" target="_blank">
                        <svg class="w-6 h-6 fill-slate-300 group-hover:fill-white transition-colors" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .018 5.396.015 12.03c0 2.12.548 4.188 1.59 6.033L0 24l6.135-1.61a11.75 11.75 0 005.91 1.586h.005c6.634 0 12.032-5.396 12.033-12.03a11.8 11.8 0 00-3.525-8.509z"/>
                        </svg>
                    </a>
                    <a class="w-12 h-12 rounded-full glass-icon flex items-center justify-center group hover:bg-[#1877F2] transition-all duration-300 border border-slate-700 hover:border-[#1877F2]"
                        href="<?php echo e($config->url_fb); ?>" target="_blank">
                        <svg class="w-6 h-6 fill-slate-300 group-hover:fill-white transition-colors" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a class="w-12 h-12 rounded-full glass-icon flex items-center justify-center group hover:bg-gradient-to-tr hover:from-yellow-400 hover:via-red-500 hover:to-purple-500 transition-all duration-300 border border-slate-700 hover:border-pink-500"
                        href="<?php echo e($config->url_ig); ?>" target="_blank">
                        <svg class="w-6 h-6 fill-slate-300 group-hover:fill-white transition-colors" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.351-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.948s-.014-3.667-.072-4.947c-.2-4.353-2.612-6.78-6.98-6.98C15.667.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4.162 4.162 0 110-8.324 4.162 4.162 0 010 8.324zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-center py-10 opacity-10 select-none">
            <h1
                class="text-6xl md:text-8xl lg:text-9xl font-display font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-transparent tracking-widest scale-y-125 uppercase">
                <?php echo e(ENV('APP_NAME')); ?>

            </h1>
        </div>

        <div 
            class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-bold tracking-widest text-slate-600 uppercase">
            <div class="order-2 md:order-1">
                © <?php echo e(date('Y')); ?> <?php echo e(ENV('APP_NAME')); ?> STORE. ALL RIGHTS RESERVED.
            </div>
            <div class="flex gap-6 order-1 md:order-2">
                <a class="hover:text-primary transition-colors" href="/id/privacy-policy">KEBIJAKAN PRIVASI</a>
                <a class="hover:text-primary transition-colors" href="/id/terms-and-condition">SYARAT & KETENTUAN</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .glass-icon {
        background: rgba(30, 41, 59, 0.4);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>
<?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/components/user/footer.blade.php ENDPATH**/ ?>