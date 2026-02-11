@extends('layouts.app2')

@section('content')
    <x-card.main title="Legal Stuff" size="lg">

        <x-card.mini>
            <div class="max-w-full p-4 mx-auto space-y-4 prose text-justify">
                <x-prose.title>Privacy Policy for Acolyte Academy</x-prose.title>
                <x-prose.subtitle>Effective Date: Draft as of 8/10/2024</x-prose.subtitle>
                
                <x-prose.section title="1. Personal Information Collection">
                    <x-prose.paragraph>Acolyte Academy collects the following personal information from its users:</x-prose.paragraph>
                    
                    <x-prose.ul>
                        <x-prose.li title="Email Address:">Used for account creation, communication, and retrieving the user's Gravatar (only a hashed version of the email is sent to the third-party service).</x-prose.li>
                        <x-prose.li title="Username:">Users may choose a username that does not have to be their real name.</x-prose.li>
                        <x-prose.li title="Payment Information:">Payment details are handled entirely by a third-party payment processor and are not stored on Acolyte Academy servers.</x-prose.li>
                    </x-prose.ul>
                </x-prose.section>
                
                <x-prose.section title="2. Use of Collected Data">
                    <x-prose.paragraph>The data collected is used solely to provide a better user experience on Acolyte Academy. Specifically:</x-prose.paragraph>
                    
                    <x-prose.ul>
                        <x-prose.li>Emails will be sent related to the user’s experience on the platform.</x-prose.li>
                        <x-prose.li>User progress on exams and exam results will be tracked to help users improve their grades and performance.</x-prose.li>
                    </x-prose.ul>
                </x-prose.section>
                
                <x-prose.section title="3. Data Sharing">
                    <x-prose.paragraph>Acolyte Academy shares data with the following third parties:</x-prose.paragraph>
                    
                    <x-prose.ul>
                        <x-prose.li title="Email Gateway:">A third-party service is used to send emails. Only the necessary information is provided to this service.</x-prose.li>
                        <x-prose.li title="Gravatar:">A hashed version of the user's email address is sent to Gravatar to retrieve their profile image.</x-prose.li>
                    </x-prose.ul>
                    
                    <x-prose.paragraph>No other third-party services are currently used, and no other data is shared with third parties.</x-prose.paragraph>
                </x-prose.section>
                
                <x-prose.section title="4. Data Storage & Retention">
                    <x-prose.paragraph>Data is stored on Acolyte Academy's database, which is also backed up to a third-party server. The last 5 exam sessions and progress for each exam taken are always kept. Older exam sessions are deleted after 6 months unless the user upgrades their account, in which case results can be kept or purged based on the user's request.</x-prose.paragraph>
                </x-prose.section>
                
                <x-prose.section title="5. Account Deletion">
                    <x-prose.paragraph>If a user wishes to delete their account:</x-prose.paragraph>
                    
                    <x-prose.ul>
                        <x-prose.li title="If You Have Not Created Any Public Exams:">The entire account, including all exam history, progress, and exams, will be deleted.</x-prose.li>
                        <x-prose.li title="If You Have Created Public Exams:">The user’s email address will be removed, and their name will be anonymized. Their private exams will be deleted, but public exams will remain under an anonymous listing. The user will not be able to recover their account or public exams.</x-prose.li>
                    </x-prose.ul>
                    
                    <x-prose.paragraph>Public exams associated with anonymized accounts may be deleted if no one has taken the exam after 12 months. If someone has taken the exam, it will remain to preserve that user’s history.</x-prose.paragraph>
                </x-prose.section>
                
                <x-prose.section title="6. User Rights">
                    <x-prose.paragraph>Users have the right to:</x-prose.paragraph>
                    
                    <x-prose.ul>
                        <x-prose.li>Update and correct their personal information at any time.</x-prose.li>
                        <x-prose.li>Request information about their data via the forums or by sending an email (to be determined).</x-prose.li>
                        <x-prose.li>Users cannot change their exam data but can improve their grades by continuing to take exams.</x-prose.li>
                    </x-prose.ul>
                </x-prose.section>
                
                <x-prose.section title="7. Public Profile Information">
                    <x-prose.paragraph>The following information will be visible to other users on your public profile:</x-prose.paragraph>
                    
                    <x-prose.ul>
                        <x-prose.li title="Username:">The name you choose to represent yourself on the platform.</x-prose.li>
                        <x-prose.li title="Gravatar:">The profile image associated with your email address.</x-prose.li>
                        <x-prose.li title="Public Exams:">A list of public exams you have created or taken.</x-prose.li>
                        <x-prose.li title="Mastery Rank:">Your highest mastery rank in the exams you have taken.</x-prose.li>
                    </x-prose.ul>
                    
                    <x-prose.paragraph>No other personal information, such as your email address, will be visible to other users.</x-prose.paragraph>
                </x-prose.section>
                
                <x-prose.section title="8. Cookies">
                    <x-prose.paragraph>Acolyte Academy uses cookies solely for session management. No third-party cookies are used, and no other tracking technologies are employed.</x-prose.paragraph>
                </x-prose.section>
                
                <x-prose.section title="9. Children’s Privacy">
                    <x-prose.paragraph>Acolyte Academy does not allow children under the age of 13 to use the platform, in compliance with COPPA regulations.</x-prose.paragraph>
                </x-prose.section>
                
                <x-prose.section title="10. Policy Changes">
                    <x-prose.paragraph>Any changes to this Privacy Policy will be communicated to users via email. Users will also be alerted on their first login after a change occurs, at which time they can read the updated policy. Continued use of the platform implies acceptance of the updated Privacy Policy.</x-prose.paragraph>
                </x-prose.section>                
        </x-card.mini>
    </x-card.main>
@endsection