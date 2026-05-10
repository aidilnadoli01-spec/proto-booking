<?php

namespace App\Notifications;

use App\Models\Antrean;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QueueCalledNotification extends Notification
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
        $nomor = (string) $this->antrean->nomor_antrean;
        $tanggal = (string) $this->antrean->tanggal_periksa;

        return (new MailMessage)
            ->subject('Antrean Kamu Dipanggil')
            ->greeting('Halo '.$notifiable->name)
            ->line("Nomor antrean kamu sedang dipanggil.")
            ->line("Dokter: {$dokter}")
            ->line("Tanggal: {$tanggal}")
            ->line("Nomor antrean: #{$nomor}")
            ->action('Lihat Detail Antrean', route('booking.index'));
    }
}

