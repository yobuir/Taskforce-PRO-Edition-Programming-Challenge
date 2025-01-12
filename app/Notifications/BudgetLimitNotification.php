<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetLimitNotification extends Notification
{
    use Queueable;

    public $budgetName;
    public $currentAmount;
    public $limit;
    /**
     * Create a new notification instance.
     */
    public function __construct($budgetName, $currentAmount, $limit)
    {
        $this->budgetName = $budgetName;
        $this->currentAmount = $currentAmount;
        $this->limit = $limit;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }



    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("Your budget '{$this->budgetName}' is below the set limit.")
            ->line('Current Amount: $' . $this->currentAmount)
            ->line('Limit: $' . $this->limit)
            ->action('Manage Budgets', url('/dashboard/budgets'))
            ->line('Please adjust your spending accordingly.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'budget_name' => $this->budgetName,
            'current_amount' => $this->currentAmount,
            'limit' => $this->limit,
            'message' => "The budget '{$this->budgetName}' is below its limit."
        ];
    }
}
