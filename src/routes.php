<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->group('/user', function () use ($app) {
        $app->get("/getUser/", function (Request $request, Response $response, array $args){
            $id = $request->getQueryParam("id");
            $email = $request->getQueryParam("email");
    
            $user = $this->db->table('user');
    
            if($id){
                $user = $user->where('id', $id);
            }
            if($email){
                $user = $user->where('email', $email);
            }
    
            $user = $user->first();
    
            $user->company_id = $this->db->table('company')->where('id', $user->company_id)->first();
    
            if(!$user) {
                return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
            }
            return $response->withJson(["status" => "success", "data" => $user], 200);
        });

        $app->get("/getListUser/", function (Request $request, Response $response, array $args){
            $id = $request->getQueryParam("id");
            $email = $request->getQueryParam("email");
    
            $user = $this->db->table('user');
    
            if($id){
                $user = $user->where('id', $id);
            }
            if($email){
                $user = $user->where('email', $email);
            }
    
            $user = $user->get();
    
            foreach($user as $u){
                $u->company_id = $this->db->table('company')->where('id', $u->company_id)->first();
            }
    
            if($user->count() == 0) {
                return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
            }
            return $response->withJson(["status" => "success", "data" => $user], 200);
        });
    
        $app->post("/createUser/", function (Request $request, Response $response, array $args){
            $input = $request->getParsedBody();
    
            $value = [
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'account' => $input['account'],
                'company_id' => $input['company_id'],
            ];
    
            $user = $this->db->table('user')->insert($value);
    
            if(!$user) {
                return $this->response->withJson(['status' => 'error', 'message' => 'add data unsuccessfull.'],200); 
            }
            return $response->withJson(["status" => "success", 'message' => 'add data successfull.'], 200);
        });
    
        $app->post("/updateUser/{id}/", function (Request $request, Response $response, array $args){
            $user_id = (int)$args['id'];
            $input = $request->getParsedBody();
    
            $value = [
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'account' => $input['account'],
                'company_id' => $input['company_id'],
            ];
    
            $user = $this->db->table('user')
                ->where('id', $user_id)
                ->update($value);
    
            if(!$user) {
                return $this->response->withJson(['status' => 'error', 'message' => 'update data unsuccessfull.'],200); 
            }
            return $response->withJson(["status" => "success", 'message' => 'update data successfull.'], 200);
        });
    
        $app->post("/deleteUser/{id}/", function (Request $request, Response $response, array $args){
            $user_id = (int)$args['id'];
    
            $user = $this->db->table('user')
                ->where('id', $user_id)
                ->delete();
    
            if(!$user) {
                return $this->response->withJson(['status' => 'error', 'message' => 'delete data unsuccessfull.'],200); 
            }
            return $response->withJson(["status" => "success", 'message' => 'delete data successfull.'], 200);
        });
    });

    $app->group('/company', function () use ($app) {
        $app->get("/getCompany/", function (Request $request, Response $response, array $args){
            $id = $request->getQueryParam("id");
    
            $company = $this->db->table('company');
    
            if($id){
                $company = $company->where('id', $id);
            }
    
            $company = $company->first();
    
            if(!$company) {
                return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
            }
            return $response->withJson(["status" => "success", "data" => $company], 200);
        });
    
        $app->get("/getListCompany/", function (Request $request, Response $response, array $args){
            $id = $request->getQueryParam("id");
    
            $company = $this->db->table('company');
    
            if($id){
                $company = $company->where('id', $id);
            }
    
            $company = $company->get();
    
            if($company->count() == 0) {
                return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
            }
            return $response->withJson(["status" => "success", "data" => $company], 200);
        });
    
        $app->post("/createCompany/", function (Request $request, Response $response, array $args){
            $input = $request->getParsedBody();
    
            $value = [
                'name' => $input['name'],
                'address' => $input['address'],
            ];
    
            $company = $this->db->table('company')->insert($value);
    
            if(!$company) {
                return $this->response->withJson(['status' => 'error', 'message' => 'add data unsuccessfull.'],200); 
            }
            return $response->withJson(["status" => "success", 'message' => 'add data successfull.'], 200);
        });
    
        $app->post("/updateCompany/{id}/", function (Request $request, Response $response, array $args){
            $company_id = (int)$args['id'];
            $input = $request->getParsedBody();
    
            $value = [
                'name' => $input['name'],
                'address' => $input['address'],
            ];
    
            $company = $this->db->table('company')
                ->where('id', $company_id)
                ->update($value);
    
            if(!$company) {
                return $this->response->withJson(['status' => 'error', 'message' => 'update data unsuccessfull.'],200); 
            }
            return $response->withJson(["status" => "success", 'message' => 'update data successfull.'], 200);
        });
    
        $app->post("/deleteCompany/{id}/", function (Request $request, Response $response, array $args){
            $company_id = (int)$args['id'];
    
            $company = $this->db->table('company')
                ->where('id', $company_id)
                ->delete();
    
            if(!$company) {
                return $this->response->withJson(['status' => 'error', 'message' => 'delete data unsuccessfull.'],200); 
            }
            return $response->withJson(["status" => "success", 'message' => 'delete data successfull.'], 200);
        });
    });

    $app->group('/budget-company', function () use ($app) {
        $app->get("/getBudgetCompany/", function (Request $request, Response $response, array $args){
            $id = $request->getQueryParam("id");
    
            $budgetCompany = $this->db->table('company_budget');
    
            if($id){
                $budgetCompany = $budgetCompany->where('id', $id);
            }
    
            $budgetCompany = $budgetCompany->first();
    
            $budgetCompany->company_id = $this->db->table('company')->where('id', $budgetCompany->company_id)->first();
    
            if(!$budgetCompany) {
                return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
            }
            return $response->withJson(["status" => "success", "data" => $budgetCompany], 200);
        });
    
        $app->get("/getListBudgetCompany/", function (Request $request, Response $response, array $args){
            $id = $request->getQueryParam("id");
    
            $budgetCompany = $this->db->table('company_budget');
    
            if($id){
                $budgetCompany = $budgetCompany->where('id', $id);
            }
    
            $budgetCompany = $budgetCompany->get();
    
            foreach($budgetCompany as $b){
                $b->company_id = $this->db->table('company')->where('id', $b->company_id)->first();
            }
    
            if($budgetCompany->count() == 0) {
                return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
            }
            return $response->withJson(["status" => "success", "data" => $budgetCompany], 200);
        });
    });

    $app->group('/transaction', function () use ($app) {
        $app->get("/getLogTransaction/", function (Request $request, Response $response, array $args){
            $transaction = $this->db->table('transaction')->get();
    
            foreach($transaction as $t){    
                $t->user_id = $this->db->table('user')
                    ->select('first_name', 'account', 'company_id')
                    ->where('id', $t->user_id)
                    ->first();
    
                $t->user_id->company_id = $this->db->table('company')
                    ->select('id', 'name')
                    ->where('id', $t->user_id->company_id)
                    ->first();
    
                $t->remaining_amount = $this->db->table('company_budget')
                    ->where('company_id', $t->user_id->company_id->id)
                    ->first()->amount;
            }
    
            if($transaction->count() == 0) {
                return $this->response->withJson(['status' => 'error', 'message' => 'no result data.'],200); 
            }
            return $response->withJson(["status" => "success", "data" => $transaction], 200);
        });
    
        $app->post("/addTransaction/", function (Request $request, Response $response, array $args){
            $input = $request->getParsedBody();
    
            $value = [
                'type' => $input['transaction_type'],
                'user_id' => $input['user_id'],
                'amount' => $input['amount'],
                'date' => date("Y-m-d h:i:s")
            ];
    
            if($input['amount'] <= 0){
                return $this->response->withJson(['status' => 'error', 'message' => 'amount not null.'],400);
            }
    
            $user = $this->db->table('user')->where('id', $input['user_id'])->first();
            if(!$user){
                return $this->response->withJson(['status' => 'error', 'message' => 'user not found.'],400);
            }
    
            $budgetCompany = $this->db->table('company_budget')->where('company_id', $user->company_id)->first();
            if(!$budgetCompany){
                return $this->response->withJson(['status' => 'error', 'message' => 'company not found.'],400);
            }
    
            if($input['transaction_type'] == 'R' || $input['transaction_type'] == 'C'){
                if($input['amount'] > $budgetCompany->amount){
                    return $this->response->withJson(['status' => 'error', 'message' => 'the company budget is not enough.'],400);
                }else{
                    $transaction = $this->db->table('transaction')->insert($value);
    
                    if($transaction){
                        $company_budget = $this->db->table('company_budget')
                        ->where('company_id', $user->company_id)
                        ->update([
                            'amount' => $budgetCompany->amount - $input['amount']
                        ]);
    
                        return $response->withJson(["status" => "success", 'message' => 'add data successfull.'], 200);
                    }else{
                        return $this->response->withJson(['status' => 'error', 'message' => 'add data unsuccessfull.'],400);
                    }
                }
            }else{
                $transaction = $this->db->table('transaction')->insert($value);
    
                if($transaction){
                    $company_budget = $this->db->table('company_budget')
                        ->where('company_id', $user->company_id)
                        ->update([
                            'amount' => $budgetCompany->amount + $input['amount']
                        ]);
                    
                    return $response->withJson(["status" => "success", 'message' => 'add data successfull.'], 200);
                }else{
                    return $this->response->withJson(['status' => 'error', 'message' => 'add data unsuccessfull.'],400);
                }
            }
        });
    });
};
