<?php
    $site_name = $config->judul_web ?? config('app.name');
    $site_description = $config->deskripsi_web ?? '';
    $current_url = url()->current();
    
    // Default values if not passed
    $page_title = (isset($title) && $title) ? $title . ' - ' . $site_name : $site_name;
    $page_description = (isset($description) && $description) ? $description : $site_description;
    $page_image = (isset($image) && $image) ? $image : (isset($config->logo_favicon) ? url($config->logo_favicon) : asset('favicon.ico'));
?>

<!-- Primary Meta Tags -->
<title><?php echo e($page_title); ?></title>
<meta name="title" content="<?php echo e($page_title); ?>">
<meta name="description" content="<?php echo e($page_description); ?>">
<link rel="canonical" href="<?php echo e($current_url); ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo e($current_url); ?>">
<meta property="og:title" content="<?php echo e($page_title); ?>">
<meta property="og:description" content="<?php echo e($page_description); ?>">
<meta property="og:image" content="<?php echo e($page_image); ?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?php echo e($current_url); ?>">
<meta property="twitter:title" content="<?php echo e($page_title); ?>">
<meta property="twitter:description" content="<?php echo e($page_description); ?>">
<meta property="twitter:image" content="<?php echo e($page_image); ?>">

<!-- Meta Tags for better indexing -->
<meta name="robots" content="index, follow">
<meta name="language" content="Indonesian">
<meta name="revisit-after" content="7 days">
<meta name="author" content="<?php echo e($site_name); ?>">

<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "<?php echo e($site_name); ?>",
  "url": "<?php echo e(url('/')); ?>",
  "description": "<?php echo e($site_description); ?>",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "<?php echo e(url('/')); ?>/invoices?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
<?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/components/user/seo.blade.php ENDPATH**/ ?>