<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\GalleriesToBeEmailed;
use App\Mail\GalleryUrlEmail;
use Illuminate\Support\Facades\Mail;

class SendGalleryUrlViaEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:gallery-url-via-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send gallery URLs via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // for testing
        // php artisan send:gallery-url-via-email
        
        $galleries_to_be_sent = GalleriesToBeEmailed::where('status', 'Pending')
            ->with(['gallery_download'])
            ->take(100)
            ->get();

        foreach($galleries_to_be_sent as $gallery){

            if($gallery->gallery_download && $gallery->gallery_download->status == 'Completed'){
                // send the email to the gallery->send_to with gallery url 
                if ($this->send_gallery_url_email($gallery->gallery_download->url, $gallery->send_to)){
                    $gallery->status = 'Completed';
                    $gallery->sent_at = Carbon::now();
                    $gallery->save();
                }
            }

            $this->info('Processing gallery: ' . $gallery);
        }

        return Command::SUCCESS;
    }

    function send_gallery_url_email($url, $email){
        try {
            Mail::to($email)->send(
                new GalleryUrlEmail($url)
            );

            return true;

        } catch (\Exception $e) {

            $this->error('Email failed: ' . $e->getMessage());

            return false;
        }
    }
}