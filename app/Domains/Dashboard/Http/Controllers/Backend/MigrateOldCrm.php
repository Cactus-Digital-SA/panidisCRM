<?php

namespace App\Domains\Dashboard\Http\Controllers\Backend;

use App\Domains\CountryCodes\Repositories\Eloquent\Models\CountryCode;
use Illuminate\Support\Facades\DB;

class MigrateOldCrm
{

    public function fetchUsers()
    {
        $oldUsers = DB::connection('mysql_old')->table('users')->get();

        foreach ($oldUsers as $oldUser) {
            DB::connection('mysql')->table('users')->insert([
                'id' => $oldUser->id,
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'name' => $oldUser->name,
                'email' => $oldUser->email,
                'password' => $oldUser->password,
                'email_verified_at' => $oldUser->email_verified_at,
                'password_changed_at' => $oldUser->password_changed_at,
                'active' => $oldUser->active,
                'last_login_at' => $oldUser->last_login_at,
                'last_login_ip' => $oldUser->last_login_ip,
                'to_be_logged_out' => $oldUser->to_be_logged_out,
                'profile_photo_path' => $oldUser->profile_photo_path,
            ]);
        }

        $oldUsers = DB::connection('mysql_old')->table('user_details')->get();

        foreach ($oldUsers as $oldUser) {
            try{
                DB::connection('mysql')->table('user_details')->insert([
                    'id' => $oldUser->id,
                    'user_id' => $oldUser->user_id,
                    'first_name' => $oldUser->first_name,
                    'last_name' => $oldUser->last_name,
                    'phone' => $oldUser->phone,
                    'phone_confirmed' => $oldUser->phone_confirmed,
                    'phone_confirmed_at' => $oldUser->phone_confirmed_at,
                    'birthday' => $oldUser->birthday,
                ]);
            }catch (\Exception $exception){

            }

        }

        return redirect()->route('home')->with('success', 'Users fetched successfully');
    }


    public function fetchExtraData()
    {
        $oldExtraData = DB::connection('mysql_old')->table('extra_data')->get();

        foreach ($oldExtraData as $oldExtraDatum) {
            DB::connection('mysql')->table('extra_data')->insert([
                'id' => $oldExtraDatum->id,
                'name' => $oldExtraDatum->name,
                'description' => $oldExtraDatum->description,
                'type' => $oldExtraDatum->type,
                'options' => $oldExtraDatum->options,
                'required' => $oldExtraDatum->required,
                'multiple' => $oldExtraDatum->multiple,
            ]);
        }

        $oldExtraDataModels = DB::connection('mysql_old')->table('extra_data_models')->get();

        foreach ($oldExtraDataModels as $oldExtraDataModel) {
            DB::connection('mysql')->table('extra_data_models')->insert([
                'id' => $oldExtraDataModel->id,
                'model' => $oldExtraDataModel->model,
                'extra_data_id' => $oldExtraDataModel->extra_data_id,
            ]);
        }

        $oldUserExtraData = DB::connection('mysql_old')->table('user_extra_data')->get();

        foreach ($oldUserExtraData as $oldUserExtraDatum) {
            try {
                DB::connection('mysql')->table('user_extra_data')->insert([
                    'id' => $oldUserExtraDatum->id,
                    'user_id' => $oldUserExtraDatum->user_id,
                    'extra_data_id' => $oldUserExtraDatum->extra_data_id,
                    'value' => $oldUserExtraDatum->value,
                    'sort' => $oldUserExtraDatum->sort,
                ]);
            }catch (\Exception $exception){

            }
        }

        return redirect()->route('home')->with('success', 'Users fetched successfully');

    }

    public function fetchCompanies(){
        $oldCompanies = DB::connection('mysql_old')->table('companies')->get();

        foreach ($oldCompanies as $oldCompany) {
            try {
                $country = null;
                $oldCountry = null;
                if($oldCompany->country_id){
                    $oldCountry = DB::connection('mysql_old')->table('country_codes')->find($oldCompany->country_id);
                    $country = CountryCode::where('name', $oldCountry->name)->first();
                }
                DB::connection('mysql')->table('companies')->insert([
                    'id' => $oldCompany->id,
                    'name' => $oldCompany->name,
                    'email' => $oldCompany->email,
                    'phone' => $oldCompany->phone ?? null,
                    'activity' => $oldCompany->activity ?? null,
                    'country_id' => $country->id ?? null,
                    'city' => $oldCompany->city ?? null,
                    'website' => $oldCompany->website ?? null,
                ]);
            }catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }

        $oldUserCompanies = DB::connection('mysql_old')->table('users_companies')->get();
        foreach ($oldUserCompanies as $oldUserCompany) {
            try {
                DB::connection('mysql')->table('users_companies')->insert([
                    'id' => $oldUserCompany->id,
                    'user_id' => $oldUserCompany->user_id,
                    'company_id' => $oldUserCompany->company_id,
                ]);
            }catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }

        return redirect()->route('home')->with('success', 'Companies fetched successfully');
    }

    public function fetchLeads()
    {
        $oldLeads = DB::connection('mysql_old')->table('leads')->get();
        foreach ($oldLeads as $oldLead) {
            try {
                DB::connection('mysql')->table('leads')->insert([
                    'id' => $oldLead->id,
                    'company_id' => $oldLead->company_id,
                ]);
            } catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }

        $oldClients = DB::connection('mysql_old')->table('clients')->get();
        foreach ($oldClients as $oldClient) {
            try {
                DB::connection('mysql')->table('clients')->insert([
                    'id' => $oldClient->id,
                    'company_id' => $oldClient->company_id
                ]);
            } catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }


        return redirect()->route('home')->with('success', 'Leads fetched successfully');
    }

    public function fetchNotesAndFiles()
    {
        $oldNotes = DB::connection('mysql_old')->table('notes')->get();
        foreach ($oldNotes as $oldNote) {
            try {
                DB::connection('mysql')->table('notes')->insert([
                    'id' => $oldNote->id,
                    'user_id' => $oldNote->user_id,
                    'content' => $oldNote->content,
                    'created_at' => $oldNote->created_at,
                    'updated_at' => $oldNote->updated_at,
                ]);
            } catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }

        $oldNotables = DB::connection('mysql_old')->table('notables')->get();
        foreach ($oldNotables as $oldNotable) {
            try {
                DB::connection('mysql')->table('notables')->insert([
                    'id' => $oldNotable->id,
                    'note_id' => $oldNotable->note_id,
                    'notable_type' => $oldNotable->notable_type,
                    'notable_id' => $oldNotable->notable_id,
                    'created_at' => $oldNotable->created_at,
                    'updated_at' => $oldNotable->updated_at,
                ]);
            } catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }

        $oldFiles = DB::connection('mysql_old')->table('files')->get();
        foreach ($oldFiles as $oldFile) {
            try {
                DB::connection('mysql')->table('files')->insert([
                    'id' => $oldFile->id,
                    'name' => $oldFile->name,
                    'path' => $oldFile->path,
                    'file_name' => $oldFile->file_name,
                    'mime_type' => $oldFile->mime_type,
                    'extension' => $oldFile->extension,
                    'size' => $oldFile->size,
                    'uploaded_by' => $oldFile->uploaded_by,
                    'created_at' => $oldFile->created_at,
                    'updated_at' => $oldFile->updated_at,
                ]);
            } catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }

        $oldFileables = DB::connection('mysql_old')->table('fileables')->get();
        foreach ($oldFileables as $oldFileable) {
            try {
                DB::connection('mysql')->table('fileables')->insert([
                    'id' => $oldFileable->id,
                    'file_id' => $oldFileable->file_id,
                    'fileable_type' => $oldFileable->fileable_type,
                    'fileable_id' => $oldFileable->fileable_id,
                    'created_at' => $oldFileable->created_at,
                    'updated_at' => $oldFileable->updated_at,
                ]);
            } catch (\Exception $exception){
                \Log::error($exception->getMessage());
            }
        }

        return redirect()->route('home')->with('success', 'Notes and files fetched successfully');
    }
}
