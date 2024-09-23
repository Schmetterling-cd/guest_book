<?php

namespace Tests\Feature\Administration\Services;

use App\Services\Administration\Permission\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class PermissionServiceTest extends TestCase
{

    use RefreshDatabase;

    private const RESOURCES_KEYS_PERMISSION = ['permissionId', 'permissionName'];


    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = resolve(PermissionService::class);
        $this->service->setData($this->getTestData());

    }

    private function getTestData(): array {

        return [
            'permissionId' => '1',
            'permissionName' => 'TestPermission name',
        ];

    }

    public function testGetPermission()
    {

        $permission = $this->service->get()->toArray(new Request());

        $this->assertNotFalse($permission, 'Метод get. Ошибка: ' . $this->service->getError());

        foreach (static::RESOURCES_KEYS_PERMISSION as $key) {
            $this->assertArrayHasKey($key, $permission, 'Метод get. Каждый элемент должен иметь свойство' . $key . '.');
        }

    }

    public function testAddPermission()
    {

        $permissionId = $this->service->add();

        $this->assertNotFalse($permissionId, 'Метод add. Ошибка: ' . $this->service->getError());

    }

    public function testUpdatePermission()
    {

        $permissionId = $this->service->update();

        $this->assertNotFalse($permissionId, 'Метод update. Ошибка: ' . $this->service->getError());

    }

    public function testDeletePermission()
    {

        $this->assertNotFalse($this->service->delete(), 'Метод delete. Ошибка: ' . $this->service->getError());

    }

    public function testGetPermissionList()
    {

        $permissions = $this->service->getList()->toArray(new Request());

        $this->assertNotFalse($permissions, 'Метод getList. Ошибка: ' . $this->service->getError());

        foreach ($permissions as $permission) {
            foreach (static::RESOURCES_KEYS_PERMISSION as $key) {
                $this->assertArrayHasKey($key, $permission, 'Метод get. Каждый элемент списка должен иметь свойство' . $key . '.');
            }
        }

    }

}
