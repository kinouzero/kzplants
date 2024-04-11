<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\Dashboard;
use App\Models\Item;
use App\Models\Plant;
use App\Models\Preference;
use App\Models\Property;
use App\Models\Role;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\Tag;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
  /**
   * Seed the application's database.
   */
  public function run(): void {

    // User admin
    $user = User::factory()->create([
      'name' => env('SEEDER_USER_NAME', 'User 1'),
      'email' => env('SEEDER_USER_MAIL', 'email@example.com'),
      'password' => Hash::make(env('SEEDER_USER_PWD', 'user')),
    ]);

    // Roles
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'user']);
    $roles = Role::all();
    $user->roles()->attach($roles);

    // Preferences
    Preference::create(['key' => 'lang', 'name' => 'Language', 'type' => 'select', 'options' => json_encode(['en' => 'English', 'fr' => 'French'])]);
    Preference::create(['key' => 'theme', 'name' => 'Dark mode', 'type' => 'select', 'options' => json_encode(['dark' => 'Dark', 'light' => 'Light'])]);
    Preference::create(['key' => 'table-length', 'name' => 'Table length', 'type' => 'select', 'options' => json_encode([10 => 10, 25 => 25, 50 => 50, 100 => 100])]);
    Preference::create(['key' => 'timezone', 'name' => 'Timezone', 'type' => 'text']);
    Preference::create(['key' => 'flush', 'name' => 'Flush weeks', 'type' => 'number']);
    Preference::create(['key' => 'interval-watering-chemical', 'name' => 'Interval between chemical', 'type' => 'number']);
    Preference::create(['key' => 'interval-watering-water', 'name' => 'Interval between water', 'type' => 'number']);

    // Status
    $statutNew = Statut::create([
      'name' => 'New',
      'color' => '#b51a00',
    ]);
    $statutGerm = Statut::create([
      'name' => 'Germinating',
      'color' => '#149215',
    ]);
    $statutGrowth = Statut::create([
      'name' => 'Growering',
      'color' => '#00a3d7',
    ]);
    $statutFlower = Statut::create([
      'name' => 'Flowering',
      'color' => '#ff8647',
    ]);
    $statutDry = Statut::create([
      'name' => 'Drying',
      'color' => '#fec700',
    ]);
    $statutCuring = Statut::create([
      'name' => 'Maturing',
      'color' => '#929292',
    ]);
    $statutReady = Statut::create([
      'name' => 'Ready',
      'color' => '#00f900',
    ]);
    $statutFinished = Statut::create([
      'name' => 'Finished',
      'color' => '#000000',
    ]);

    // Tags
    $tagSativa = Tag::create([
      'name' => 'Sativa',
      'color' => '#ff2600',
    ]);
    $tag8w = Tag::create([
      'name' => '8 weeks',
      'color' => '#d357fe',
    ]);
    Tag::create([
      'name' => 'Indica',
      'color' => '#008cb4',
    ]);
    Tag::create([
      'name' => 'Hybrid',
      'color' => '#f5ec00',
    ]);
    Tag::create([
      'name' => '9 weeks',
      'color' => '#be38f3',
    ]);
    Tag::create([
      'name' => '10 weeks',
      'color' => '#9929bd',
    ]);
    Tag::create([
      'name' => '11 weeks',
      'color' => '#7b219f',
    ]);
    Tag::create([
      'name' => '12 weeks',
      'color' => '#61177c',
    ]);

    // Properties
    $propsUrl        = Property::create(['name' => 'URL']);
    $propsDesc       = Property::create(['name' => 'Description']);
    $propsGene       = Property::create(['name' => 'Genetics']);
    $propsParents    = Property::create(['name' => 'Parents']);
    $propsThc        = Property::create(['name' => '% THC']);
    $propsCbd        = Property::create(['name' => '% CBD']);
    $propsSmells     = Property::create(['name' => 'Smells']);
    $propsEffects    = Property::create(['name' => 'Effects']);
    $propsStock      = Property::create(['name' => 'Stock']);
    $propsAvgYield   = Property::create(['name' => 'Average yield']);
    $propsSensor     = Property::create(['name' => 'Sensor']);
    Property::create(['name' => 'FinalYield']);

    // Checklists
    $checklistGerm      = Checklist::create(['name' => 'Germination', 'icon' => 'fas fa-leaf']);
    $checklistGrowth    = Checklist::create(['name' => 'Growth', 'icon' => 'fas fa-seedling']);
    $checklistFlower8w  = Checklist::create(['name' => 'Flower 8 weeks', 'icon' => 'fab fa-pagelines']);
    $checklistFlower9w  = Checklist::create(['name' => 'Flower 9 weeks', 'icon' => 'fab fa-pagelines']);
    $checklistFlower10w = Checklist::create(['name' => 'Flower 10 weeks', 'icon' => 'fab fa-pagelines']);
    $checklistFlower11w = Checklist::create(['name' => 'Flower 11 weeks', 'icon' => 'fab fa-pagelines']);
    $checklistFlower12w = Checklist::create(['name' => 'Flower 12 weeks', 'icon' => 'fab fa-pagelines']);
    $checklistHarvest   = Checklist::create(['name' => 'Harvest', 'icon' => 'fas fa-jar']);

    // Checklist statut
    $checklistStatut = [
      $checklistGerm->id      => $statutGerm->id,
      $checklistGrowth->id    => $statutGrowth->id,
      $checklistFlower8w->id  => $statutFlower->id,
      $checklistFlower9w->id  => $statutFlower->id,
      $checklistFlower10w->id => $statutFlower->id,
      $checklistFlower11w->id => $statutFlower->id,
      $checklistFlower12w->id => $statutFlower->id,
      $checklistHarvest->id   => $statutDry->id,
    ];

    // Checklist parents
    $checklistGrowth->parents()->sync([$checklistGerm->id]);
    $checklistFlower8w->parents()->sync([$checklistGrowth->id]);
    $checklistFlower9w->parents()->sync([$checklistGrowth->id]);
    $checklistFlower10w->parents()->sync([$checklistGrowth->id]);
    $checklistFlower11w->parents()->sync([$checklistGrowth->id]);
    $checklistFlower12w->parents()->sync([$checklistGrowth->id]);
    $checklistHarvest->parents()->sync([$checklistFlower8w->id, $checklistFlower9w->id, $checklistFlower10w->id, $checklistFlower11w->id, $checklistFlower12w->id]);

    // Items
    $items = [];
    for ($i = 1; $i <= 4; $i++) {
      foreach ([
        $checklistGrowth, $checklistFlower8w, $checklistFlower9w, $checklistFlower10w, $checklistFlower11w, $checklistFlower12w,
      ] as $checklist) $items[$checklist->id][$i] = Item::create(['name' => sprintf('Week %d', $i), 'parent_id' => $i > 1 ? $items[$checklist->id][$i - 1]->id : null, 'checklist_id' => $checklist->id, 'statut_id' => $checklistStatut[$checklist->id]]);
    }
    for ($i; $i <= 8; $i++) {
      foreach ([
        $checklistFlower8w, $checklistFlower9w, $checklistFlower10w, $checklistFlower11w, $checklistFlower12w,
      ] as $checklist) $items[$checklist->id][$i] = Item::create(['name' => sprintf('Week %d', $i), 'parent_id' => $i > 1 ? $items[$checklist->id][$i - 1]->id : null, 'checklist_id' => $checklist->id, 'statut_id' => $checklistStatut[$checklist->id]]);
    }
    foreach ([
      $checklistFlower9w, $checklistFlower10w, $checklistFlower11w, $checklistFlower12w,
    ] as $checklist) $items[$checklist->id][9] = Item::create(['name' => 'Week 9', 'parent_id' => $items[$checklist->id][8]->id, 'checklist_id' => $checklist->id, 'statut_id' => $checklistStatut[$checklist->id]]);
    foreach ([
      $checklistFlower10w, $checklistFlower11w, $checklistFlower12w,
    ] as $checklist) $items[$checklist->id][10] = Item::create(['name' => 'Week 10', 'parent_id' => $items[$checklist->id][9]->id, 'checklist_id' => $checklist->id, 'statut_id' => $checklistStatut[$checklist->id]]);
    foreach ([
      $checklistFlower11w, $checklistFlower12w,
    ] as $checklist) $items[$checklist->id][11] = Item::create(['name' => 'Week 11', 'parent_id' => $items[$checklist->id][10]->id,  'checklist_id' => $checklist->id, 'statut_id' => $checklistStatut[$checklist->id]]);
    $items[$checklistFlower12w->id][12] = Item::create(['name' => 'Week 12', 'parent_id' => $items[$checklistFlower12w->id][11]->id, 'checklist_id' => $checklistFlower12w->id, 'statut_id' => $checklistStatut[$checklist->id]]);
    $itemGerm = Item::create(['name' => 'Germinating', 'checklist_id' => $checklistGerm->id, 'statut_id' => $checklistStatut[$checklistGerm->id]]);
    Item::create(['name' => 'Potting', 'parent_id' => $itemGerm->id, 'checklist_id' => $checklistGerm->id, 'statut_id' => $checklistStatut[$checklistGerm->id]]);
    $itemHang = Item::create(['name' => 'Hanging', 'checklist_id' => $checklistHarvest->id, 'statut_id' => $checklistStatut[$checklistHarvest->id]]);
    $itemDry = Item::create(['name' => 'Drying', 'parent_id' => $itemHang->id, 'checklist_id' => $checklistHarvest->id, 'statut_id' => $checklistStatut[$checklistHarvest->id]]);
    Item::create(['name' => 'Curing', 'parent_id' => $itemDry->id, 'checklist_id' => $checklistHarvest->id, 'statut_id' => $checklistStatut[$checklistHarvest->id]]);

    // Strain
    $strain        = Strain::create(['name' => 'Strain 1']);
    $strainProps   = $strainTags = [];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsUrl->id, 'value' => 'https://google.com'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsDesc->id, 'value' => 'Description test'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsGene->id, 'value' => '75% Sativa'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsParents->id, 'value' => 'Parent 1 x Parent 2'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsThc->id, 'value' => '30'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsCbd->id, 'value' => 'Low'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsSmells->id, 'value' => 'Good'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsEffects->id, 'value' => 'Strong'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsStock->id, 'value' => '10'];
    $strainProps[] = ['strain_id' => $strain->id, 'property_id' => $propsAvgYield->id, 'value' => '750'];
    $strainTags[]  = ['strain_id' => $strain->id, 'tag_id' => $tagSativa->id];
    $strainTags[]  = ['strain_id' => $strain->id, 'tag_id' => $tag8w->id];
    $strain->properties()->sync($strainProps);
    $strain->tags()->sync($strainTags);

    // Plant
    $plant        = Plant::create(['name' => 'Plant 1', 'strain_id' => $strain->id, 'created_by' => $user->id, 'statut_id' => $statutNew->id]);
    $plantProps   = $plantTags = [];
    $plantProps[] = ['plant_id' => $plant->id, 'property_id' => $propsSensor->id, 'value' => 'A'];
    $plant->properties()->sync($plantProps);
    $plant->tags()->sync($plantTags);

    // Plant first checklist
    $plant->checklists()->sync([$checklistGerm->id => ['initial' => true], $checklistGrowth->id => ['initial' => false], $checklistFlower8w->id => ['initial' => false], $checklistHarvest->id => ['initial' => false]]);

    // Plant flush
    $plant->items()->sync([$items[$checklistFlower8w->id][7]->id => ['flush' => true], $items[$checklistFlower8w->id][8]->id => ['flush' => true]]);

    // Dashboards
    $board1 = Dashboard::create(['name' => 'Board 1']);
    $board2 = Dashboard::create(['name' => 'Board 2']);
    $board3 = Dashboard::create(['name' => 'Board 3']);
    $board4 = Dashboard::create(['name' => 'Board 4']);
    $board1->users()->sync([$user->id, ['creator' => true, 'default' => true]]);
    $board2->users()->sync([$user->id, ['creator' => true, 'default' => false]]);
    $board3->users()->sync([$user->id, ['creator' => true, 'default' => false]]);
    $board4->users()->sync([$user->id, ['creator' => true, 'default' => false]]);
    $board1->plants()->sync([$plant->id]);
  }
}
