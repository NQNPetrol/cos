<?php

use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\UpdateTeamNameForm;
use Livewire\Livewire;

beforeEach(function () {
    if (! Features::hasTeamFeatures()) {
        $this->markTestSkipped('Jetstream teams están deshabilitados en config/jetstream.php.');
    }
});

test('team names can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(UpdateTeamNameForm::class, ['team' => $user->currentTeam])
        ->set(['state' => ['name' => 'Test Team']])
        ->call('updateTeamName');

    expect($user->fresh()->ownedTeams)->toHaveCount(1);
    expect($user->currentTeam->fresh()->name)->toEqual('Test Team');
});
