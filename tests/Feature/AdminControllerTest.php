<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class AdminControllerTest extends TestCase
{
    // DONE: Create an ACP page
    #[Test]
    public function acp_page_exists(): void
    {
        $this->CreateAdminAndAuthenticate();

        $response = $this->get(route('admin.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.index');
    }

    // DONE: Create a User List page
    #[Test]
    public function acp_links_to_users_page(): void
    {
        $this->CreateAdminAndAuthenticate();

        $response = $this->get(route('admin.index'));

        $response->assertSee(route('admin.users'));
    }

    #[Test]
    public function users_page_loads(): void
    {
        $this->CreateAdminAndAuthenticate();

        $response = $this->get(route('admin.users'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.users');
    }

    #[Test]
    public function users_are_shown_on_users_page(): void
    {
        $user = $this->CreateAdminAndAuthenticate();

        $response = $this->get(route('admin.users'));

        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    // DONE: Create a User Manage page
    #[Test]
    public function user_manage_page_is_linked_from_users_page(): void
    {
        $admin = $this->CreateAdminAndAuthenticate();
        $user = $this->CreateUser();

        $response = $this->get(route('admin.users'));

        $response->assertSee(route('admin.user', $user));
    }

    #[Test]
    public function user_manage_page_loads(): void
    {
        $admin = $this->CreateAdminAndAuthenticate();
        $user = $this->CreateUser();

        $response = $this->get(route('admin.user', $user));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.user');
        $response->assertSee($user->email);
    }

    #[Test]
    public function user_update_page_saves_data(): void
    {
        $admin = $this->CreateAdminAndAuthenticate();
        $user = $this->CreateUser();

        $data = $this->getFormData('user');

        $response = $this->post(route('admin.user-update', $user), $data);

        $data['id'] = $user->id;
        $this->assertDatabaseHas('users', $data);
    }

    #[Test]
    #[DataProvider('validUserFormData')]
    public function user_update_form_data_validates($field, $value): void
    {
        $admin = $this->CreateAdminAndAuthenticate();
        $user = $this->CreateUser();

        $data = $this->getFormData('user');

        $data[$field] = $value;

        $response = $this->post(route('admin.user-update', $user), $data);

        $data['id'] = $user->id;
        $this->assertDatabaseHas('users', $data);
    }

    public static function validUserFormData()
    {
        return [
            ['name', 'a'],
            ['name', 'Normal Test'],
            ['name', 'Really Long Name Test 12345asdfjbasdfup'],
            ['email', 'TestEmail@sfi.org'],
        ];
    }

    #[Test]
    #[DataProvider('invalidUserFormData')]
    public function user_update_form_data_rejects_bad_data($field, $value): void
    {
        $admin = $this->CreateAdminAndAuthenticate();
        $user = $this->CreateUser();

        $data = $this->getFormData('user');

        $data[$field] = $value;

        $response = $this->post(route('admin.user-update', $user), $data);

        $data['id'] = $user->id;
        $response->assertSessionHasErrors($field);
    }

    public static function invalidUserFormData()
    {
        return [
            ['name', ''],
            ['name', null],
            ['email', ''],
            ['email', null],
            ['email', 'This is some text'],
            ['email', 'Name'],
        ];
    }

    #[Test]
    public function user_update_page_redirects_to_main_users_index(): void
    {
        $admin = $this->CreateAdminAndAuthenticate();
        $user = $this->CreateUser();

        $data = $this->getFormData('user');

        $response = $this->post(route('admin.user-update', $user), $data);

        $response->assertRedirect(route('admin.users'));
    }

    // TODO: Set a user as an admin in their manage page -- Create and use the field isAdmin

    // TODO: Only admins can access the ACP page
    #[Test]
    #[DataProvider('adminPages')]
    public function users_cannot_access_admin_pages($route, $method, $model): void
    {
        $user = $this->CreateUserAndAuthenticate();
        $useRoute = null;
        $data = [];

        switch ($model) {
            case 'user':
                $useRoute = route($route, $user);
                $data = $this->getFormData('user');
                break;

            default:
                $useRoute = route($route);
                break;
        }

        if ($method == 'get') {
            $response = $this->get($useRoute);
        } else {
            $response = $this->post($useRoute, $data);
        }

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    #[Test]
    #[DataProvider('adminPages')]
    public function admins_can_access_admin_pages($route, $method, $model): void
    {
        $user = $this->CreateAdminAndAuthenticate();
        $useRoute = null;
        $data = [];

        switch ($model) {
            case 'user':
                $useRoute = route($route, $user);
                $data = $this->getFormData('user');
                break;

            default:
                $useRoute = route($route);
                break;
        }

        if ($method == 'get') {
            $response = $this->get($useRoute);
        } else {
            $response = $this->followingRedirects()->post($useRoute, $data);
        }

        $response->assertStatus(Response::HTTP_OK);
    }

    public static function adminPages()
    {
        return [
            ['admin.index', 'get', null],
            ['admin.users', 'get', null],
            ['admin.user', 'get', 'user'],
            ['admin.user-update', 'post', 'user'],
        ];
    }

    /** ========== HELPER FUNCTIONS ========== */
    private function getFormData($type)
    {
        $data = [];

        if ($type == 'user') {
            $data = [
                'name' => 'Test User',
                'email' => 'TestUser@jedi.com',
            ];
        }

        return $data;
    }
}
