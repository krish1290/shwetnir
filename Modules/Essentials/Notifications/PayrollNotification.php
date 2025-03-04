<?php

namespace Modules\Essentials\Notifications;

use App\User;
use App\Utils\NotificationUtil;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollNotification extends Notification
{
    use Queueable;

    protected $payroll;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payroll)
    {
        $this->payroll = $payroll;
        $notificationUtil = new NotificationUtil();
        $notificationUtil->configureEmail($payroll);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $channels = ['database', 'mail'];
        if (isPusherEnabled()) {
            $channels[] = 'broadcast';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = $this->payroll;

        $month = \Carbon::parse($data['transaction_date'])->format('F');
        $year = \Carbon::parse($data['transaction_date'])->format('Y');

        $created_by = User::find($data['created_by']);
        $user = User::find($data['expense_for']);
        $msg = __('essentials::lang.payroll_added_notification', ['month_year' => $month . '/' . $year, 'ref_no' => $data['ref_no'], 'created_by' => $created_by->user_full_name]);
        $data['email_body'] = "<p>Dear " . $user->user_full_name . ",</p><p> Your " . $msg . ". The respective payroll is attached here with.</p>";
        $data['subject'] = "Payroll-" . $month . "/" . $year;

        $mail = (new MailMessage)
            ->subject($data['subject'])
            ->view(
                'emails.plain_html',
                ['content' => $data['email_body']]
            );
        //dd($mail);
        if (!empty($data['pdf']) && !empty($data['pdf_name'])) {
            $mail->attachData($data['pdf']->Output($data['pdf_name'], 'S'), $data['pdf_name'], [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $transaction_date = \Carbon::parse($this->payroll->transaction_date);

        return [
            'month' => $transaction_date->format('m'),
            'year' => $transaction_date->format('Y'),
            'ref_no' => $this->payroll->ref_no,
            'action' => $this->payroll->action,
            'created_by' => $this->payroll->created_by,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        $msg = '';
        $title = '';
        $transaction_date = \Carbon::parse($this->payroll->transaction_date);
        $month = \Carbon::createFromFormat('m', $transaction_date->format('m'))->format('F');
        if ($this->payroll->action == 'created') {
            $msg = __('essentials::lang.payroll_added_notification', ['month_year' => $month . '/' . $transaction_date->format('Y'), 'ref_no' => $this->payroll->ref_no, 'created_by' => $this->payroll->sales_person->user_full_name]);
            $title = __('essentials::lang.payroll_added');
        } elseif ($this->payroll->action == 'updated') {
            $msg = __('essentials::lang.payroll_updated_notification', ['month_year' => $month . '/' . $transaction_date->format('Y'), 'ref_no' => $this->payroll->ref_no, 'created_by' => $this->payroll->sales_person->user_full_name]);
            $title = __('essentials::lang.payroll_updated');
        }

        return new BroadcastMessage([
            'title' => $title,
            'body' => $msg,
            'link' => action([\Modules\Essentials\Http\Controllers\PayrollController::class, 'index']),
        ]);
    }
}
