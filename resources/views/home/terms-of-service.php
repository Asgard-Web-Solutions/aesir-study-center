@extends('layouts.app2')

@section('content')
    <x-card.main title="Legal Stuff" size="lg">

        <x-card.mini>
            <div class="max-w-full p-4 mx-auto space-y-4 prose text-justify">
            <x-prose.title>Terms of Service for Acolyte Academy</x-prose.title>
                <x-prose.subtitle>Effective Date: [Insert Date]</x-prose.subtitle>

                <x-prose.section title="1. User Roles & Responsibilities">
                    <x-prose.paragraph>All users on Acolyte Academy are called Acolytes, and within that, there are several roles: Adept, Mage, and Keeper.</x-prose.paragraph>

                    <x-prose.ul>
                        <x-prose.li title="Adept (Free Account):">
                            Permitted Actions:
                            <x-prose.ul>
                                <x-prose.li>Create, share, and participate in exams.</x-prose.li>
                                <x-prose.li>Interact with other users through comments (when available).</x-prose.li>
                                <x-prose.li>Access content provided under the free account level.</x-prose.li>
                            </x-prose.ul>
                            Optional Nature: Adepts are not required to engage in any specific activities; participation is voluntary.
                        </x-prose.li>
                        
                        <x-prose.li title="Mage (Paid Account):">
                            Permitted Actions:
                            <x-prose.ul>
                                <x-prose.li>Create, share, and participate in exams with full access to all features.</x-prose.li>
                                <x-prose.li>Interact with other users through comments (when available).</x-prose.li>
                                <x-prose.li>Access premium content and features exclusive to Mages.</x-prose.li>
                            </x-prose.ul>
                            Optional Nature: Mages have access to additional features, but like Adepts, they are not obligated to engage in any specific activities.
                        </x-prose.li>

                        <x-prose.li title="Keeper (Admin):">
                            Responsibilities:
                            <x-prose.ul>
                                <x-prose.li>Ensure the smooth operation of the site by overseeing its technical and user-related aspects.</x-prose.li>
                                <x-prose.li>Monitor the platform to identify and remove harmful, illegal, or inappropriate content.</x-prose.li>
                                <x-prose.li>Enforce the site’s rules and guidelines, ensuring a safe and respectful environment for all users.</x-prose.li>
                            </x-prose.ul>
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>

                <x-prose.section title="2. Prohibited Actions">
                    <x-prose.ul>
                        <x-prose.li title="Prohibited Content:">
                            Users must not post, share, or link to content that is harmful, illegal, or violates the rights of others. This includes, but is not limited to, content that is defamatory, obscene, promotes violence or discrimination, or contains sexual content.
                        </x-prose.li>

                        <x-prose.li title="Respectful Interaction:">
                            All users must treat others with respect. Harassment, abusive language, and disruptive behavior are strictly prohibited. Users are expected to engage in discussions and interactions in a constructive and respectful manner.
                        </x-prose.li>

                        <x-prose.li title="Integrity in Exams:">
                            Cheating, manipulating exam results, or otherwise undermining the integrity of the exam process is not allowed. Users must not use unauthorized means to alter their own or others' performance data.
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>

                <x-prose.section title="3. Content Ownership & Licensing">
                    <x-prose.ul>
                        <x-prose.li title="User Ownership:">
                            Users retain ownership of any exam information they add to Acolyte Academy. Users have the ability to modify or delete their content at any time.
                        </x-prose.li>

                        <x-prose.li title="Public Exam Licensing:">
                            When a user makes an exam public, they grant Acolyte Academy permission to use the exam and its content. This permission includes:
                            <x-prose.ul>
                                <x-prose.li>Allowing other users to take the public exams.</x-prose.li>
                                <x-prose.li>Using the content in Acolyte Academy's promotional materials, including digital and print media.</x-prose.li>
                                <x-prose.li>Displaying exam names and questions as part of marketing efforts.</x-prose.li>
                            </x-prose.ul>
                            Once the exam content has been used in marketing materials, the user cannot revoke this permission by making the exam private or deleting it.
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>

                <x-prose.section title="4. Account Management">
                    <x-prose.ul>
                        <x-prose.li title="Account Creation:">
                            Users must provide a valid email address that they own when creating an account. Usernames can be chosen freely but must adhere to Acolyte Academy's content guidelines, ensuring they are appropriate and do not violate any rules regarding prohibited content.
                        </x-prose.li>

                        <x-prose.li title="Account Maintenance:">
                            Users are responsible for maintaining the confidentiality of their account information and are liable for all activities that occur under their account. Acolyte Academy reserves the right to suspend or terminate accounts that are found to be in violation of the Terms of Service or other policies.
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>

                <x-prose.section title="5. Payment & Refunds">
                    <x-prose.ul>
                        <x-prose.li title="Payment Processing:">
                            Users can unlock full access to Acolyte Academy by subscribing to a paid account (Mage). All subscription payments will be processed through Stripe, a secure third-party payment processor. Users must provide valid payment information to complete their subscription.
                        </x-prose.li>

                        <x-prose.li title="Refund Policy:">
                            Refunds will be issued upon request if made within ten days of the subscription payment date. Users can request a refund directly from their profile page. Once the refund request is processed, access to the paid features will be revoked.
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>

                <x-prose.section title="6. Dispute Resolution">
                    <x-prose.ul>
                        <x-prose.li title="Informal Resolution:">
                            Users are encouraged to first contact Acolyte Academy directly to resolve any disputes or issues informally. This can be done through the dedicated support channel or email provided on the platform. Acolyte Academy will make reasonable efforts to resolve any concerns quickly and fairly.
                        </x-prose.li>

                        <x-prose.li title="Mediation:">
                            If an issue cannot be resolved informally, both parties may agree to participate in mediation. Mediation is a voluntary process where a neutral third-party mediator helps facilitate a mutually acceptable resolution. Mediation can take place in Colorado, USA, or through an online mediation service.
                        </x-prose.li>

                        <x-prose.li title="Small Claims Court:">
                            For disputes involving smaller amounts of money, either party may choose to bring the issue to small claims court in Colorado, USA. This option provides a quicker and more cost-effective way to resolve certain types of disputes.
                        </x-prose.li>

                        <x-prose.li title="Governing Law:">
                            All disputes arising under these Terms of Service will be governed by the laws of Colorado, USA.
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>

                <x-prose.section title="7. Termination of Services">
                    <x-prose.ul>
                        <x-prose.li title="Grounds for Termination:">
                            Acolyte Academy reserves the right to suspend or terminate a user's account if they are found to be in violation of the Terms of Service, particularly regarding content restrictions and prohibited actions.
                        </x-prose.li>

                        <x-prose.li title="Account Suspension:">
                            If an account is suspended, the user will be locked out and unable to access or make changes to their data. The suspended user’s private exams may be deleted after six months if the suspension is not lifted.
                        </x-prose.li>

                        <x-prose.li title="Public Exams:">
                            Any public exams created by the suspended user will remain publicly available on the platform, even if the user’s account is suspended.
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>

                <x-prose.section title="8. Liability Disclaimer">
                    <x-prose.ul>
                        <x-prose.li title="Service Availability:">
                            Acolyte Academy will conduct regular maintenance every other Monday. During these times, the platform may be temporarily unavailable. While we strive to maintain uptime on a “best effort” basis, there is no guarantee of uninterrupted access to the platform.
                        </x-prose.li>

                        <x-prose.li title="Data Backup & Recovery:">
                            Acolyte Academy will back up data nightly and maintain multiple copies of the database to ensure recoverability within reason. However, we do not guarantee that all data will be recoverable in every situation.
                        </x-prose.li>

                        <x-prose.li title="Third-Party Content:">
                            Acolyte Academy is not responsible for the content posted by other users or third parties on the platform. Users are solely responsible for the content they share.
                        </x-prose.li>

                        <x-prose.li title="No Guarantees:">
                            The Acolyte Academy platform is provided on an “as is” and “as available” basis, without any warranties or guarantees of any kind. This includes, but is not limited to, implied warranties of merchantability, fitness for a particular purpose, and non-infringement.
                        </x-prose.li>

                        <x-prose.li title="Service Termination:">
                            If Acolyte Academy’s service is ever to be terminated, users will receive a minimum of six months' notification before the service is retired.
                        </x-prose.li>
                    </x-prose.ul>
                </x-prose.section>
        </x-card.mini>
    </x-card.main>
@endsection