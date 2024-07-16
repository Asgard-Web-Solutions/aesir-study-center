<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreateUserAndAuthenticate;
    use CreateQuestionGroup;
    use CreatesApplication;
    use RefreshDatabase;
    use CreateQuestion;
    use CreateUser;
    use CreateSet;
    use TakeTest;
}
