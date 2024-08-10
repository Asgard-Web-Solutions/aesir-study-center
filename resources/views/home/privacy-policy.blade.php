@extends('layouts.app2')

@section('content')
    <x-card.main title="Legal Stuff" size="xl">

        <x-card.mini>
            <div class="max-w-full p-4 mx-auto space-y-4 prose text-justify">
                <h1 class="mb-6 text-3xl font-bold text-center">Privacy Policy for Acolyte Academy</h1>
                <p class="mb-10"><strong>Effective Date:</strong> DRAFT POLICY as of 8/10/2024. Final version coming soon...</p>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">1. Personal Information Collection</h2>
                <p class="mb-10">Acolyte Academy collects the following personal information from its users:</p>
                <ul class="mb-4 ml-4 list-disc list-inside">
                    <li class="mb-3"><strong>Email Address:</strong> Used for account creation, communication, and retrieving the user's Gravatar (only a hashed version of the email is sent to the third-party service).</li>
                    <li class="mb-3"><strong>Username:</strong> Users may choose a username that does not have to be their real name.</li>
                </ul>
                <p class="mb-10"><strong>Payment Information:</strong> Payment details are handled entirely by a third-party payment processor and are not stored on Acolyte Academy servers.</p>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">2. Use of Collected Data</h2>
                <p class="mb-10">The data collected is used solely to provide a better user experience on Acolyte Academy. Specifically:</p>
                <ul class="mb-4 ml-4 list-disc list-inside">
                    <li class="mb-3">Emails will be sent related to the user’s experience on the platform.</li>
                    <li class="mb-3">User progress on exams and exam results will be tracked to help users improve their grades and performance.</li>
                </ul>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">3. Data Sharing</h2>
                <p class="mb-10">Acolyte Academy shares data with the following third parties:</p>
                <ul class="mb-4 ml-4 list-disc list-inside">
                    <li class="mb-3"><strong>Email Gateway:</strong> A third-party service is used to send emails. Only the necessary information is provided to this service.</li>
                    <li class="mb-3"><strong>Gravatar:</strong> A hashed version of the user's email address is sent to Gravatar to retrieve their profile image.</li>
                </ul>
                <p class="mb-10">No other third-party services are currently used, and no other data is shared with third parties.</p>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">4. Data Storage & Retention</h2>
                <p class="mb-10">Data is stored on Acolyte Academy's database, which is also backed up to a third-party server. The last 5 exam sessions and progress for each exam taken are always kept. Older exam sessions are deleted after 6 months unless the user upgrades their account, in which case results can be kept or purged based on the user's request.</p>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">5. Account Deletion</h2>
                <p class="mb-10">If a user wishes to delete their account:</p>
                <ul class="mb-4 ml-4 list-disc list-inside">
                    <li class="mb-3"><strong>If No Public Exams Created:</strong> The entire account, including exam history, progress, and exams, will be deleted.</li>
                    <li class="mb-3"><strong>If Public Exams Were Created:</strong> The user’s email address will be removed, and their name will be anonymized. Their private exams will be deleted, but public exams will remain under an anonymous listing. The user will not be able to recover their account or public exams.</li>
                </ul>
                <p class="mb-10">Public exams associated with anonymized accounts may be deleted if no one has taken the exam after 12 months. If someone has taken the exam, it will remain to preserve that user’s history.</p>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">6. User Rights</h2>
                <p class="mb-10">Users have the right to:</p>
                <ul class="mb-4 ml-4 list-disc list-inside">
                    <li class="mb-3">Update and correct their personal information at any time.</li>
                    <li class="mb-3">Request information about their data via the forums or by sending an email (to be determined).</li>
                    <li class="mb-3">Users cannot change their exam data but can improve their grades by continuing to take exams.</li>
                </ul>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">7. Cookies</h2>
                <p class="mb-10">Acolyte Academy uses cookies solely for session management. No third-party cookies are used, and no other tracking technologies are employed.</p>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">8. Children’s Privacy</h2>
                <p class="mb-10">Acolyte Academy does not allow children under the age of 13 to use the platform, in compliance with COPPA regulations.</p>
            
                <h2 class="mt-8 mb-3 text-2xl font-semibold">9. Policy Changes</h2>
                <p class="mb-10">Any changes to this Privacy Policy will be communicated to users via email. Users will also be alerted on their first login after a change occurs, at which time they can read the updated policy. Continued use of the platform implies acceptance of the updated Privacy Policy.</p>
            </div>
            
        </x-card.mini>
    </x-card.main>
@endsection