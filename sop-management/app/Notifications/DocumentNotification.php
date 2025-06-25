<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class DocumentNotification extends Notification
{
    protected $document;
    protected $action; // tambah/edit/hapus
    protected $admin;

    public function __construct($document, $action, $admin)
    {
        $this->document = $document;
        $this->action = $action;
        $this->admin = $admin;
    }

    public function via($notifiable)
    {
        // Tambahkan 'mail' jika ingin juga mengirim lewat email
        return ['database', 'mail'];
    }

    /**
     * Format notifikasi untuk database
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => "Dokumen {$this->action}",
            'body' => "Dokumen '{$this->document->judul}' telah di-{$this->action} oleh {$this->admin->name}",
            'document_id' => $this->document->id,
            'action' => $this->action,
        ];
    }

    /**
     * Format notifikasi untuk email
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Notifikasi: Dokumen {$this->action}")
            ->greeting("Halo {$notifiable->name},")
            ->line("Dokumen berjudul '{$this->document->judul}' telah di-{$this->action} oleh {$this->admin->name}.")
            ->action('Lihat Dokumen', url(route('documents.preview', $this->document->id)))
            ->line('Terima kasih telah menggunakan sistem dokumentasi kami!');
    }
}
