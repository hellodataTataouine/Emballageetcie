<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\Product;
class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
  

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

    // Add static URLs
    $sitemap->add(Url::create('/'))
            ->add(Url::create('/about'))
            ->add(Url::create('/products'))
              ->add(Url::create('/pages/contact-us'))
              ->add(Url::create('/pages/about-us'))
              ->add(Url::create('/pages/terms-conditions'))
              
            ->add(Url::create('/contact'));

    // Add dynamic URLs from the database
    $products = Product::all();
    foreach ($products as $product) {
        $sitemap->add(Url::create("/products/{$product->slug}"));
    }

    $categories = Category::all();
    foreach ($categories as $category) {
        $sitemap->add(Url::create("/products?category_id={$category->id}"));
       
    }

   


    $sitemap->writeToFile(public_path('sitemap.xml'));

    $this->info('Sitemap generated!');
    }
}
