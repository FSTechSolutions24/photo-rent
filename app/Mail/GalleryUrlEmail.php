<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GalleryUrlEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $galleryUrl;

    public function __construct($galleryUrl)
    {
        $this->galleryUrl = $galleryUrl;
    }

    public function build()
    {
        return $this
            ->subject('Your Gallery Is Ready')
            ->view('emails.gallery_url');
    }
}