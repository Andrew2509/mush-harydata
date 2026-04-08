<section id="testimonials" class="w-full relative group">
    <!-- Animated background icons -->
    <div class="absolute top-0 left-0 w-full h-[300px] pointer-events-none overflow-visible z-0">
        <div
            class="absolute -top-10 left-[10%] w-20 h-20 md:w-32 md:h-32 bg-slate-600/10 border border-white/5 rounded-3xl rotate-45 backdrop-blur-[1px] animate-rotate-slow flex items-center justify-center overflow-hidden">
            <i
                class="fas fa-shield-alt text-white/10 text-4xl md:text-6xl transform -rotate-45 drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]"></i>
        </div>
        <div
            class="absolute top-0 right-[20%] w-16 h-16 md:w-24 md:h-24 bg-slate-500/10 border border-white/5 rounded-2xl rotate-12 backdrop-blur-[1px] animate-rotate-reverse flex items-center justify-center overflow-hidden">
            <i
                class="fas fa-gem text-primary/20 text-3xl md:text-5xl transform -rotate-12 drop-shadow-[0_0_10px_rgba(14,165,233,0.3)]"></i>
        </div>
        <div
            class="absolute top-[40%] left-[55%] w-10 h-10 md:w-16 md:h-16 bg-white/5 border border-white/5 rounded-xl rotate-45 backdrop-blur-[1px] animate-rotate-slow flex items-center justify-center overflow-hidden">
            <i
                class="fas fa-bolt text-yellow-400/20 text-xl md:text-3xl transform -rotate-45 drop-shadow-[0_0_8px_rgba(250,204,21,0.3)]"></i>
        </div>
        <div
            class="absolute top-[30%] right-[5%] w-24 h-24 md:w-40 md:h-40 bg-slate-700/10 border border-white/5 rounded-[1.5rem] md:rounded-[2rem] rotate-[30deg] backdrop-blur-[1px] animate-rotate-reverse delay-700 flex items-center justify-center overflow-hidden">
            <i
                class="fas fa-gamepad text-secondary/10 text-5xl md:text-8xl transform -rotate-[30deg] drop-shadow-[0_0_15px_rgba(249,115,22,0.2)]"></i>
        </div>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 md:mb-10 gap-6 relative z-10">
        <div class="max-w-2xl relative z-10">
            <h2
                class="text-3xl md:text-5xl font-display font-black text-white mb-2 md:mb-4 drop-shadow-[0_0_10px_rgba(14,165,233,0.5)] uppercase">
                TESTIMONIALS
                <span class="inline-block w-2 h-2 md:w-3 md:h-3 bg-primary rounded-full ml-1 animate-pulse"></span>
            </h2>
            <p class="text-slate-400 text-sm md:text-lg leading-relaxed font-body font-medium">
                Terima kasih untuk semua pelanggan yang memberi kami ulasan dan peringkat. Kepercayaan Anda adalah
                prioritas utama kami dalam menyediakan layanan top-up terbaik.
            </p>
        </div>
        <div class="flex gap-2">
            <button
                class="w-10 h-10 rounded-full border border-slate-600 flex items-center justify-center hover:bg-primary hover:border-primary hover:text-white transition-all shadow-[0_0_10px_rgba(14,165,233,0)] hover:shadow-[0_0_15px_rgba(14,165,233,0.6)]">
                <span class="material-icons">chevron_left</span>
            </button>
            <button
                class="w-10 h-10 rounded-full border border-slate-600 flex items-center justify-center hover:bg-primary hover:border-primary hover:text-white transition-all shadow-[0_0_10px_rgba(14,165,233,0)] hover:shadow-[0_0_15px_rgba(14,165,233,0.6)]">
                <span class="material-icons">chevron_right</span>
            </button>
        </div>
    </div>

    <!-- Scrollable Body -->
    <div class="w-full overflow-hidden relative z-10">
        <div class="absolute top-0 left-0 w-16 h-full bg-gradient-to-r from-background-dark to-transparent z-10 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-16 h-full bg-gradient-to-l from-background-dark to-transparent z-10 pointer-events-none"></div>
        <div class="flex w-max animate-scroll hover:[animation-play-state:paused] gap-6 py-4">
            <?php if($ratings->isEmpty()): ?>
                <div class="w-full py-10 text-center text-slate-500 font-bold uppercase tracking-widest opacity-50">
                    Belum ada testimoni.
                </div>
            <?php else: ?>
                <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.testimonial_card', ['rating' => $rating], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                
                <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.testimonial_card', ['rating' => $rating], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/components/user/testimonials.blade.php ENDPATH**/ ?>