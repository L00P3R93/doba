<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Privacy | Doba Play')]
#[Layout('layouts.marketing')]
class Privacy extends Component
{
    public string $effectiveDate = '[EFFECTIVE DATE]';
    public string $contactEmail = '[LEGAL CONTACT EMAIL]';

    /**
     * Section content for the Privacy Policy page.
     *
     * @var array<int, array{
     *     id: string,
     *     title: string,
     *     paragraphs: array<int, string>,
     *     definitions?: array<string, string>,
     *     notice?: string
     * }>
     */
    public array $sections = [];

    public function mount(): void
    {
        $this->sections = [
            [
                'id' => 'introduction',
                'title' => 'Introduction',
                'paragraphs' => [
                    'DobaPlay ("DobaPlay", "we", "us", or "our") respects your privacy and is committed to handling your personal information responsibly. This Privacy Policy explains what information we collect, how we use it, and the choices available to you as a listener, creator, or advertiser on the Platform.',
                    'This Policy applies to the DobaPlay website, applications, and related services. It should be read alongside our Terms of Service.',
                ],
            ],
            [
                'id' => 'information-we-collect',
                'title' => 'Information We Collect',
                'paragraphs' => [
                    'We collect information you provide directly to us, information generated automatically through your use of the Platform, and, in limited cases, information from third parties such as distribution or payment partners.',
                ],
            ],
            [
                'id' => 'information-you-provide',
                'title' => 'Information You Provide',
                'paragraphs' => [
                    'This includes information you enter when you register, update your profile, contact support, or otherwise interact with us directly, such as your name, email address, phone number, and any content or messages you submit.',
                ],
            ],
            [
                'id' => 'account-information',
                'title' => 'Account Information',
                'paragraphs' => [
                    'When you create a listener or creator account, we collect account details such as your name, email address, phone number, account type (Artist, Studio, Record Label, Event, Filmmaker, or Listener), and authentication information.',
                ],
            ],
            [
                'id' => 'creator-content-information',
                'title' => 'Creator / Content Information',
                'paragraphs' => [
                    'For creator accounts, we collect the content you upload (music, video, podcast, or film files), associated metadata (titles, artwork, credits, release details), and information about your distribution destinations and performance analytics.',
                ],
            ],
            [
                'id' => 'payment-information',
                'title' => 'Payment Information',
                'paragraphs' => [
                    'When you subscribe to a plan or receive a payout, we collect payment-related information such as your M-Pesa phone number, transaction identifiers, and billing history. Card or mobile money credentials are processed by our payment providers and are not stored in full on DobaPlay\'s own systems.',
                ],
            ],
            [
                'id' => 'usage-technical-information',
                'title' => 'Usage and Technical Information',
                'paragraphs' => [
                    'We automatically collect information about how you use the Platform, including pages viewed, features used, streaming and playback activity, timestamps, and approximate location derived from your IP address.',
                ],
            ],
            [
                'id' => 'device-browser-information',
                'title' => 'Device and Browser Information',
                'paragraphs' => [
                    'We may collect information about the device and browser you use to access the Platform, including device type, operating system, browser type, and unique device identifiers, for security and compatibility purposes.',
                ],
            ],
            [
                'id' => 'cookies',
                'title' => 'Cookies and Similar Technologies',
                'paragraphs' => [
                    'We use cookies and similar technologies to keep you signed in, remember your preferences, and understand how the Platform is used. You can control cookies through your browser settings; disabling certain cookies may affect Platform functionality.',
                ],
            ],
            [
                'id' => 'how-we-use-information',
                'title' => 'How We Use Information',
                'paragraphs' => [
                    'We use the information we collect to provide and operate the Platform, process subscriptions and payouts, distribute content to selected partners, communicate with you about your account, maintain security, and improve our services.',
                    'We may also use aggregated or de-identified information for analytics and reporting purposes that do not identify you individually.',
                ],
            ],
            [
                'id' => 'how-we-share-information',
                'title' => 'How We Share Information',
                'paragraphs' => [
                    'We share information as necessary to operate the Platform: with distribution partners to publish your content, with payment and payout providers to process transactions, and with service providers who support our infrastructure, subject to appropriate confidentiality obligations.',
                    'We do not sell your personal information. We may disclose information where required by law, to protect the rights and safety of DobaPlay and its users, or in connection with a merger, acquisition, or sale of assets.',
                ],
            ],
            [
                'id' => 'service-providers',
                'title' => 'Service Providers',
                'paragraphs' => [
                    'We work with third-party service providers that help us operate the Platform, such as hosting, email delivery, and analytics providers. These providers process information on our behalf and are contractually restricted from using it for their own purposes.',
                ],
            ],
            [
                'id' => 'distribution-platform-partners',
                'title' => 'Distribution and Platform Partners',
                'paragraphs' => [
                    'When you choose to distribute content through DobaPlay, relevant content and metadata are shared with the streaming platforms and storefronts you select. Each partner processes that information under its own privacy policy, which we encourage you to review.',
                ],
            ],
            [
                'id' => 'payment-payout-providers',
                'title' => 'Payment and Payout Providers',
                'paragraphs' => [
                    'Subscription payments and creator payouts are processed through payment providers such as M-Pesa. These providers receive the information necessary to complete your transaction and are subject to their own privacy and security practices.',
                ],
            ],
            [
                'id' => 'data-security',
                'title' => 'Data Security',
                'paragraphs' => [
                    'We use reasonable administrative, technical, and physical safeguards designed to protect your information from unauthorised access, loss, or misuse. No method of transmission or storage is completely secure, and we cannot guarantee absolute security.',
                ],
            ],
            [
                'id' => 'data-retention',
                'title' => 'Data Retention',
                'paragraphs' => [
                    'We retain personal information for as long as necessary to provide the Platform, comply with our legal obligations, resolve disputes, and enforce our agreements. Retention periods vary depending on the type of information and the purpose for which it was collected.',
                ],
            ],
            [
                'id' => 'your-privacy-rights',
                'title' => 'Your Privacy Rights',
                'paragraphs' => [
                    'Depending on your location and applicable law, you may have rights to access, correct, delete, or restrict the use of your personal information, and to receive a copy of it in a portable format. You can exercise many of these rights directly from your account settings.',
                    'To make a request that cannot be completed through your account, contact us at [CONTACT EMAIL]. We will respond in accordance with applicable law.',
                ],
            ],
            [
                'id' => 'marketing-communications',
                'title' => 'Marketing Communications',
                'paragraphs' => [
                    'We may send you product updates, promotional messages, or newsletters. You can opt out of marketing communications at any time using the unsubscribe link in the message or through your account preferences; we will still send you transactional and account-related messages.',
                ],
            ],
            [
                'id' => 'childrens-privacy',
                'title' => "Children's Privacy",
                'paragraphs' => [
                    'The Platform is not directed to children under the age of 18, and we do not knowingly collect personal information from children without appropriate consent. If you believe a child has provided us with personal information, contact us at [CONTACT EMAIL] so we can take appropriate action.',
                ],
            ],
            [
                'id' => 'international-transfers',
                'title' => 'International Data Transfers',
                'paragraphs' => [
                    'Because DobaPlay works with distribution and service partners in multiple countries, your information may be transferred to, stored, and processed in jurisdictions outside your own, which may have different data protection laws than your home jurisdiction. Where required, we take steps intended to provide an appropriate level of protection for such transfers.',
                ],
            ],
            [
                'id' => 'third-party-links',
                'title' => 'Third-Party Links',
                'paragraphs' => [
                    'The Platform may contain links to third-party websites or services that are not operated by DobaPlay. We are not responsible for the privacy practices of those third parties, and we encourage you to review their privacy policies before providing any information.',
                ],
            ],
            [
                'id' => 'changes-policy',
                'title' => 'Changes to This Privacy Policy',
                'paragraphs' => [
                    'We may update this Privacy Policy from time to time to reflect changes to our practices or applicable law. We will post the updated Policy on this page and update the "Last updated" date; material changes may also be communicated by email or in-app notice where practical.',
                ],
            ],
            [
                'id' => 'contact',
                'title' => 'Contact Information',
                'paragraphs' => [
                    'If you have questions about this Privacy Policy or how we handle your information, please contact us at [CONTACT EMAIL] or write to us at [REGISTERED ADDRESS].',
                ],
            ],
        ];
    }

    public function render(): Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.⚡privacy');
    }
}
