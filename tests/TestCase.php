<?php

namespace Tests;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CompleteExamSession;
    use CreateAdminAndAuthenticate;
    use CreateQuestion;
    use CreateQuestionGroup;
    use CreateSet;
    use CreateUser;
    use CreateUserAndAuthenticate;
    use GiveUserCredits;
    use RefreshDatabase;
    use RegisterUserQuestions;
    use StartExamSession;
    use StartPracticeSession;
    use TakeTest;
}
