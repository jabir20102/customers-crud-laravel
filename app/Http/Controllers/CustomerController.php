<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }
    
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'tel' => 'required',
            'address' => 'required',
            'birthdate' => 'required',
        ];
    
        $messages = [
            'name.required' => 'The name field is required.',
            'surname.required' => 'The surname field is required.',
            'tel.required' => 'The tel field is required.',
            'address.required' => 'The address field is required.',
            'birthdate.required' => 'The birthdate field is required.',
        ];
    
        $this->validate($request, $rules, $messages);

        Customer::create($request->all());

        return redirect()->route('customers.index')
             ->with('success', 'Customer added successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'tel' => 'required|numeric',
            'address' => 'required',
            'birthdate' => 'required|date',
        ]);

        $customer->update($request->all());

        
        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $customers = Customer::where('name', 'like', '%'.$search.'%')
            ->orWhere('surname', 'like', '%'.$search.'%')
            ->orWhere('tel', 'like', '%'.$search.'%')
            ->orWhere('address', 'like', '%'.$search.'%')
            ->orWhere('birthdate', 'like', '%'.$search.'%')
            ->get();

        return view('customers.index', compact('customers'));
    }
}
