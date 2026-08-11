<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Terms of Service | DobaPlay')]
#[Layout('layouts.marketing', [
    'metaDescription' => 'Read the DobaPlay Terms & Conditions covering creator accounts, content ownership, payouts, and platform use for artists, studios, labels, events, and filmmakers.',
    'metaImage' => 'og/legal-og.png',
    'keywords' => 'dobaplay terms of service, music distribution platform terms kenya',
    'canonical' => null, // route('terms') resolves via url(request()->path())
])]
class Terms extends Component
{
    public string $effectiveDate = '[EFFECTIVE DATE]';
    public string $contactEmail = '[LEGAL CONTACT EMAIL]';

    /**
     * Section content for the Terms of Service page.
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
                    'Welcome to DobaPlay ("DobaPlay", "we", "us", or "our"), a digital distribution and monetisation platform built for artists, studios, record labels, event promoters, and filmmakers across East Africa, alongside a listening experience for music and video fans.',
                    'These Terms of Service ("Terms") govern your access to and use of the DobaPlay website, applications, and related services (collectively, the "Platform"). They apply to every visitor, registered user, creator account, and advertiser using the Platform.',
                    'DobaPlay is operated by [LEGAL ENTITY NAME], registered at [REGISTERED ADDRESS]. Please read these Terms carefully before creating an account or using any part of the Platform.',
                ],
            ],
            [
                'id' => 'acceptance',
                'title' => 'Acceptance of Terms',
                'paragraphs' => [
                    'By creating an account, uploading content, subscribing to a plan, or otherwise using the Platform, you confirm that you have read, understood, and agree to be bound by these Terms and our Privacy Policy.',
                    'If you do not agree to these Terms, you must not access or use the Platform. Continued use of the Platform after changes to these Terms are published constitutes acceptance of the revised Terms.',
                ],
            ],
            [
                'id' => 'eligibility',
                'title' => 'Eligibility',
                'paragraphs' => [
                    'You must be at least 18 years old, or the age of legal majority in your jurisdiction, to create a listener or creator account. Where a minor is permitted to use the Platform under applicable law, use must be supervised by a parent or legal guardian who accepts these Terms on the minor\'s behalf.',
                    'By registering, you represent that all information you provide is accurate and complete, and that you have the legal capacity to enter into a binding agreement with DobaPlay.',
                    'Creator accounts registered as a Studio or Record Label additionally represent that the individual creating the account has the authority to bind the business entity to these Terms.',
                ],
            ],
            [
                'id' => 'services',
                'title' => 'DobaPlay Services',
                'paragraphs' => [
                    'DobaPlay provides tools for creators to upload, distribute, and monetise music, video, podcasts, and film content, along with tools to promote events and sell tickets. The Platform also offers a premium listening experience for consumers and self-service advertising placements for advertisers.',
                    'Creator accounts are available across several tiers, including Artist, Studio, Record Label, Event, and Filmmaker/Cinema plans, each with tier-specific limits and features described on our pricing pages. We may introduce, modify, or retire specific features, tiers, or tools at our discretion, with reasonable notice for material changes.',
                ],
            ],
            [
                'id' => 'accounts',
                'title' => 'User Accounts',
                'paragraphs' => [
                    'You are responsible for maintaining the confidentiality of your account credentials and for all activity that occurs under your account. Notify us immediately at [CONTACT EMAIL] if you suspect unauthorised access to your account.',
                    'You may not share, sell, or transfer your account to another person or entity without our prior written consent. We reserve the right to suspend or terminate accounts that provide false registration information or that are used in violation of these Terms.',
                ],
            ],
            [
                'id' => 'creator-responsibilities',
                'title' => 'Creator / Content Owner Responsibilities',
                'paragraphs' => [
                    'As a creator, you are solely responsible for the content you upload, including its accuracy, legality, and compliance with these Terms. You confirm that you own the content you submit, or that you hold all rights, licences, consents, and clearances necessary to distribute it through DobaPlay and its distribution partners.',
                    'Studio and Record Label accounts are responsible for ensuring that every artist or sub-account they manage on the Platform is bound by, and complies with, these Terms.',
                    'You are responsible for keeping your metadata (titles, artwork, credits, release information) accurate and up to date, as inaccurate metadata may delay distribution or payouts.',
                ],
            ],
            [
                'id' => 'content-submission',
                'title' => 'Content Submission and Distribution',
                'paragraphs' => [
                    'When you submit content for distribution, you authorise DobaPlay to process, encode, store, and deliver that content to the streaming platforms, storefronts, and distribution partners you select, subject to each partner\'s own technical and content requirements.',
                    'Distribution timelines vary by content type, partner, and release schedule, and we do not guarantee a specific publication date. We may reject, delay, or remove a submission that fails automated or manual content checks.',
                ],
            ],
            [
                'id' => 'intellectual-property',
                'title' => 'Intellectual Property',
                'paragraphs' => [
                    'The DobaPlay name, logo, Platform design, software, and underlying technology are the property of DobaPlay or its licensors and are protected by applicable intellectual property laws. Nothing in these Terms transfers ownership of DobaPlay\'s intellectual property to you.',
                    'You retain ownership of the content you upload, subject to the licences you grant to DobaPlay described below.',
                ],
            ],
            [
                'id' => 'rights-granted',
                'title' => 'Rights and Licenses Granted to DobaPlay',
                'paragraphs' => [
                    'By submitting content to the Platform, you grant DobaPlay a non-exclusive, worldwide, royalty-bearing licence to host, reproduce, encode, distribute, publicly perform, and display that content solely for the purposes of operating, promoting, and distributing the Platform and delivering it to the destinations you select.',
                    'This licence continues for as long as your content remains active on the Platform or with a distribution partner, and terminates in relation to a specific piece of content once you remove it and it has been taken down from all connected partners, subject to standard partner processing times and any minimum retention obligations described by a partner.',
                ],
            ],
            [
                'id' => 'copyright-ownership',
                'title' => 'Copyright and Content Ownership',
                'paragraphs' => [
                    'You must not upload content that infringes the copyright, trademark, or other intellectual property rights of any third party. DobaPlay operates automated copyright detection to help identify unauthorised uploads and protect original work.',
                    'If you believe content on the Platform infringes your rights, you may submit a takedown request to [CONTACT EMAIL] with sufficient detail to identify the content and your claim. We will review and respond to valid claims in accordance with applicable law.',
                ],
            ],
            [
                'id' => 'prohibited-content',
                'title' => 'Prohibited Content and Activities',
                'paragraphs' => [
                    'You may not use the Platform to upload, distribute, or promote content that is unlawful, defamatory, obscene, hateful, or that infringes the rights of others, nor may you misrepresent your identity, manipulate streaming or engagement metrics, or attempt to circumvent Platform security or payment systems.',
                ],
                'notice' => 'Violations of this section may result in immediate content removal, account suspension, forfeiture of pending payouts related to the violation, and, where applicable, referral to law enforcement.',
            ],
            [
                'id' => 'content-removal',
                'title' => 'Content Removal / Suspension',
                'paragraphs' => [
                    'We may remove content, suspend a feature, or suspend an account, temporarily or permanently, if we reasonably believe it violates these Terms, applicable law, or a distribution partner\'s policies. Where practical, we will notify you of the action taken and the reason for it.',
                    'You may request removal of your own content at any time through your dashboard; removal from third-party partners is subject to that partner\'s own processing time.',
                ],
            ],
            [
                'id' => 'distribution-partners',
                'title' => 'Distribution Partners and Third-Party Platforms',
                'paragraphs' => [
                    'DobaPlay distributes content to a network of third-party streaming platforms and storefronts. Each partner applies its own technical specifications, content guidelines, and payout schedules, which are outside DobaPlay\'s direct control.',
                    'DobaPlay is not responsible for a partner\'s decision to reject, delay, or remove content, or for changes a partner makes to its own royalty rates or policies.',
                ],
            ],
            [
                'id' => 'plans-subscriptions',
                'title' => 'Plans and Subscriptions',
                'paragraphs' => [
                    'Creator plans (Artist, Studio, Record Label, Event, Filmmaker/Cinema) are billed on a yearly basis unless otherwise stated at checkout. Listener premium plans are available at the frequency and price displayed on our pricing page at the time of purchase.',
                    'Plan features, limits, and pricing are described on the relevant pricing page and are incorporated into these Terms by reference. Prices are shown in Kenyan Shillings (KES) unless otherwise indicated.',
                ],
            ],
            [
                'id' => 'fees-payments',
                'title' => 'Fees and Payments',
                'paragraphs' => [
                    'Fees for creator and listener plans are due at the time of purchase or renewal and are processed through our supported payment methods, including M-Pesa. You authorise us, or our payment processor, to charge the payment method associated with your subscription.',
                    'Failed or reversed payments may result in suspension of the associated plan or features until payment is successfully completed.',
                ],
            ],
            [
                'id' => 'renewals',
                'title' => 'Renewals',
                'paragraphs' => [
                    'Unless you cancel before the end of your current billing period, your plan will renew automatically at the then-current price for an additional term of the same length. We will make reasonable efforts to notify you in advance of a renewal where required by applicable law.',
                ],
            ],
            [
                'id' => 'refunds-cancellation',
                'title' => 'Refunds / Cancellation',
                'paragraphs' => [
                    'You may cancel your subscription at any time through your account settings; cancellation stops future renewals but does not automatically entitle you to a refund for the current billing period unless required by applicable law.',
                    'Fees already paid are generally non-refundable except where required by law or expressly stated at the time of purchase. Refund requests can be sent to [CONTACT EMAIL] and will be reviewed on a case-by-case basis.',
                ],
            ],
            [
                'id' => 'payouts-revenue',
                'title' => 'Payouts and Revenue',
                'paragraphs' => [
                    'Where your plan entitles you to streaming or sales revenue, payouts are calculated based on the reporting we receive from distribution partners, which may be delayed or estimated pending partner reconciliation. Payout schedules and minimum thresholds are described in your dashboard and may change from time to time.',
                    'We reserve the right to withhold or reverse a payout that we reasonably believe resulted from fraudulent activity, invalid streams, or a breach of these Terms, pending investigation.',
                ],
            ],
            [
                'id' => 'taxes',
                'title' => 'Taxes',
                'paragraphs' => [
                    'You are solely responsible for determining and paying any taxes, duties, or levies applicable to your use of the Platform, including taxes on payouts and revenue you receive. DobaPlay does not provide tax advice and recommends you consult a qualified professional in your jurisdiction.',
                ],
            ],
            [
                'id' => 'platform-availability',
                'title' => 'Platform Availability',
                'paragraphs' => [
                    'We aim to keep the Platform available and performant but do not guarantee uninterrupted or error-free operation. The Platform may be temporarily unavailable for maintenance, upgrades, or reasons outside our reasonable control.',
                ],
            ],
            [
                'id' => 'third-party-services',
                'title' => 'Third-Party Services',
                'paragraphs' => [
                    'The Platform may link to, integrate with, or rely on third-party services, including payment processors and distribution partners. Your use of those services may be subject to separate terms, and DobaPlay is not responsible for the content, policies, or practices of third parties.',
                ],
            ],
            [
                'id' => 'privacy-reference',
                'title' => 'Privacy',
                'paragraphs' => [
                    'Our collection and use of personal information in connection with the Platform is described in our Privacy Policy, which forms part of these Terms by reference.',
                ],
            ],
            [
                'id' => 'disclaimers',
                'title' => 'Disclaimers',
                'paragraphs' => [
                    'The Platform is provided on an "as is" and "as available" basis, without warranties of any kind, whether express or implied, including implied warranties of merchantability, fitness for a particular purpose, or non-infringement, to the fullest extent permitted by applicable law.',
                    'We do not guarantee any specific level of streams, sales, revenue, or audience growth resulting from your use of the Platform.',
                ],
            ],
            [
                'id' => 'limitation-liability',
                'title' => 'Limitation of Liability',
                'paragraphs' => [
                    'To the fullest extent permitted by applicable law, DobaPlay and its officers, employees, and affiliates will not be liable for any indirect, incidental, special, consequential, or punitive damages, or for any loss of revenue, profits, or data, arising from your use of the Platform.',
                    'Where liability cannot be excluded under applicable law, DobaPlay\'s aggregate liability will be limited to the amount you paid to DobaPlay in the twelve (12) months preceding the event giving rise to the claim.',
                ],
            ],
            [
                'id' => 'indemnification',
                'title' => 'Indemnification',
                'paragraphs' => [
                    'You agree to indemnify and hold DobaPlay harmless from any claims, damages, liabilities, and expenses (including reasonable legal fees) arising from your content, your breach of these Terms, or your violation of any third-party right or applicable law.',
                ],
            ],
            [
                'id' => 'termination',
                'title' => 'Termination',
                'paragraphs' => [
                    'You may close your account at any time through your account settings or by contacting [CONTACT EMAIL]. We may suspend or terminate your access to the Platform, with or without notice, for a material breach of these Terms or where required by law.',
                    'Sections of these Terms that by their nature should survive termination, including intellectual property, disclaimers, limitation of liability, and indemnification, will survive.',
                ],
            ],
            [
                'id' => 'changes-terms',
                'title' => 'Changes to the Terms',
                'paragraphs' => [
                    'We may update these Terms from time to time to reflect changes to the Platform or applicable law. We will post the updated Terms on this page and update the "Last updated" date; material changes may also be communicated by email or in-app notice where practical.',
                ],
            ],
            [
                'id' => 'governing-law',
                'title' => 'Governing Law',
                'paragraphs' => [
                    'These Terms are governed by the laws of [GOVERNING JURISDICTION], without regard to conflict of law principles, unless otherwise required by applicable local law where you reside.',
                ],
            ],
            [
                'id' => 'contact',
                'title' => 'Contact Information',
                'paragraphs' => [
                    'If you have questions about these Terms, please contact us at [CONTACT EMAIL] or write to us at [REGISTERED ADDRESS].',
                ],
            ],
        ];
    }

    public function render(): Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.⚡terms');
    }
}
