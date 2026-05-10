<?php

namespace App\Notifications;

use App\Models\Antrean;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Antrean $antrean)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dokter = $this->antrean->jadwalDokter?->dokter?->nama ?? '-';
        $tanggal = (string) $this->antrean->tanggal_periksa;
        $nomor = (string) $this->antrean->nomor_antrean;
        $jam = trim(($this->antrean->jadwalDokter?->jam_mulai ?? '-').' - '.($this->antrean->jadwalDokter?->jam_selesai ?? '-'));

        return (new MailMessage)
            ->subject('Booking Antrean Berhasil')
            ->greeting('Halo '.$notifiable->name)
            ->line("Booking antrean kamu berhasil dibuat.")
            ->line("Dokter: {$dokter}")
            ->line("Tanggal: {$tanggal}")
            ->line("Jam: {$jam}")
            ->line("Nomor antrean: #{$nomor}")
            ->action('Lihat Booking Saya', route('booking.index'));
    }
}

