<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TelegramNotification extends Notification
{
  use Queueable;

  protected $log;
  protected $user;
  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($log, $user)
  {
    //
    $this->log = $log;
    $this->user = $user;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return [TelegramChannel::class];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toTelegram($notifiable)
  {
    // return TelegramMessage::create()
    //   // ->to($notifiable->telegram_chat_id)
    //   ->to(env('TELEGRAM_ADMIN'))
    //   ->content("Có một giao dịch mới cần duyệt\nUser: " .$this->user->username. "\nSố tiền giao dịch: ".$this->log->amount)
    //   ->buttonWithCallback('Confirm', 'confirm_invoice ' . $this->log->id);
    return;
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}
