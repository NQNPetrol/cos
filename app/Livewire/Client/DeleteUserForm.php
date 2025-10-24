<?php

namespace App\Livewire\Client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public $password = '';
    public $confirmingUserDeletion = false;

    public function confirmUserDeletion()
    {
        $this->resetValidation();
        $this->password = '';
        $this->dispatch('confirming-delete-user');
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        try{
            \Log::info('Iniciando eliminación de cuenta para usuario: ' . Auth::id());
            $this->validate([
                'password' => ['required', 'current_password'],
            ]);

            \Log::info('Contraseña validada correctamente');

            $user = Auth::user();

            Auth::logout();
            \Log::info('Sesión cerrada');

            $user->delete();

            session()->invalidate();
            session()->regenerateToken();

            return redirect('/');

        } catch (ValidationException $e) {
                $this->addError('password', $e->validator->errors()->first('password'));
                throw $e;
            } catch (\Exception $e) {
                $this->addError('password', 'Error al eliminar la cuenta: ' . $e->getMessage());
            }
    }

    public function render()
    {
        return view('client.profile.delete-user-form');
    }
}
