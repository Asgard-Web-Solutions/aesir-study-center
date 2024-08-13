<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CompleteExamSession;
    use CreateAdminAndAuthenticate;
    use CreateQuestion;
    use CreateQuestionGroup;
    use CreatesApplication;
    use CreateSet;
    use CreateUser;
    use CreateUserAndAuthenticate;
    use GiveUserCredits;
    use RefreshDatabase;
    use StartExamSession;
    use StartPracticeSession;
    use TakeTest;
}
