<?php

namespace App\Jobs;

use App\Mail\ProductNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendProductNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;
    protected $type;

    public function __construct(Product $product, $type = 'store')
    {
        $this->product = $product;
        $this->type = $type;
    }

    public function handle()
    {
        $userEmail = $this->product->user->email;

        $title = 'Yeni Ürün Bildirimi';
        if ($this->type == 'store') {
            $body = "Merhaba {$this->product->user->name}, {$this->product->name} isimli ürününüz başarıyla oluşturulmuştur.";
        } elseif ($this->type == 'update') {
            $title = 'Ürün Fiyat Güncellemesi';
            $body = "Merhaba {$this->product->user->name}, {$this->product->name} isimli ürününüzün fiyatı {$this->product->price} TL olarak güncellenmiştir.";
        } elseif ($this->type == 'delete') {
            $title = 'Ürün Silme Bildirimi';
            $body = "Merhaba {$this->product->user->name}, {$this->product->name} isimli ürününüz başarıyla silinmiştir.";
        }
        Log::info($body);
        // Mail::to($userEmail)->send(new ProductNotificationMail(
        //     $title,
        //     $body
        // ));
    }
}
