<?php

use App\Models\GalleriesToBeEmailed;

$galleris_to_be_sent = GalleriesToBeEmailed::where('status', 'Pending')->with(['gallery_download'])->take(100)->get();

dd($galleris_to_be_sent);

