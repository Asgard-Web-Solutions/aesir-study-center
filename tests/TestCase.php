<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreateAdminAndAuthenticate;
    use CreateUserAndAuthenticate;
    use StartPracticeSession;
    use CompleteExamSession;
    use CreateQuestionGroup;
    use CreatesApplication;
    use StartExamSession;
    use RefreshDatabase;
    use GiveUserCredits;
    use CreateQuestion;
    use CreateUser;
    use CreateSet;
    use TakeTest;
}
