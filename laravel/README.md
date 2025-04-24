# Day 1

## Installation

Before installing Laravel on Debian, you need to make sure that all the dependencies are installed.  
Laravel depends on PHP and some extensions, as well as a database such as MariaDB or Sqlite. Here are the main packages that should be installed on Debian:

```bash
sudo apt-get install php php-common php-cli php-gd php-curl php-xml php-mbstring php-zip php-sybase php-mysql php-sqlite3
sudo apt-get install mariadb-server sqlite3 git
```

Composer is a dependency manager that allows you to easily install, update, and manage libraries and packages, ensuring that a project has all the necessary dependencies.  
In Laravel, Composer is used to install the framework and its libraries.

```bash
curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

In addition, it is important to configure the database, as it will be used to install Laravel. Let's initially create an admin user with admin password and create a database called training:

```bash
sudo mariadb
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%' IDENTIFIED BY 'admin' WITH GRANT OPTION;
CREATE DATABASE training;
quit
```

Create a folder where you will do this training, you can call it training or projects:

```bash
mkdir training
cd training
```

The following command creates a new Laravel project in the day_01 folder, downloading the basic structure of the framework and installing all the necessary dependencies via Composer, ensuring that the environment is ready for development:

```bash
composer create-project laravel/laravel day_01
cd day_01
php artisan serve
```

## MVC

A route is how the framework defines and manages URLs to access different parts of the application. Routes are configured in the routes/web.php (for web pages) or routes/api.php (for APIs) file and determine which code will be executed when a user accesses a specific URL. Example:

```php
Route::get('/example-of-route', function () {
echo "A route without a controller, not good!";
});
```

A controller is a class responsible for organizing the application logic, separating the business rules from the routes.
Instead of defining all the logic directly in the routes, controllers group related functionalities, making the code cleaner and more modular. The naming convention for controllers follows the PascalCase standard, where the name must be descriptive, in the singular and always end with “Controller”, such as ProductController or UserController. Let's create the EstagiarioController with the following command that automatically generates the corresponding file within app/Http/Controllers:

```bash
php artisan make:controller InternController
```

Next, we create the trainees route and point it to the traineeController controller, previously importing the namespace App\Http\Controllers\EstagiarioController.

The namespace is a way of organizing classes, functions and constants to avoid name conflicts in large projects. It allows you to group related elements within the same scope, facilitating code reuse and maintenance.

```php
use App\Http\Controllers\InternController;

Route::get('/interns', [InternController::class,'index']);
```

The View layer is responsible for displaying the application interface, separating the presentation logic from the business logic (controller). It uses Blade, a template language that allows you to create dynamic pages efficiently. Views are stored in the resources/views folder and can be returned from a controller using return view('view_name').

```bash
mkdir resources/views/interns
touch resources/views/interns/index.blade.php
```

In the controller:

```php
public function index()
{
return view('interns.index');
}
```

Minimal content of index.blade.php:

```html
<!DOCTYPE html>
<html>
<head>
<title>Interns</title>
</head>
<body>
John<br>
Mary
</body>
</html>
```

The Model is a representation of a table in the database and is responsible for interacting with the data in that table. It encapsulates the logic of accessing and manipulating data, allowing operations such as inserting, updating, deleting and reading records to be performed in a simple and intuitive way. Laravel uses Eloquent ORM (Object-Relational Mapping) to map database data to PHP objects, which allows you to work with tables as if they were an object class.  
  
Creating the model called Intern:

```bash
php artisan make:model Intern -m
```

Migrations are a way to version and manage the database schema, allowing you to create, change and remove tables in a controlled and traceable way. They work as a history of changes to the database, helping to maintain version control between different development and production environments.  
  
Each migration is a PHP class that defines the operations to be performed on the database. Migrations are stored in the database/migrations folder. Migrations make the database management process more organized and flexible, especially in projects with multiple developers.  
  
Let's add three columns to the Intern model: name, age and email.

```php
$table->string('name');
$table->string('email');
$table->integer('age');
```

Migrate to the database.

```bash
php artisan migrate
```

## Challenge

Create a route called interns/create pointing to the create method in InternController, which must also be created.  
  
In the create method of InternController, insert the interns:

```php
public function create(){
$intern1 = new \App\Models\Intern;
$intern1->name = "John";
$intern1->email = "john@usp.br";
$intern1->age = 26;
$intern1->save();

$intern2 = new \App\Models\Intern;
$intern2->name = "Mary";
$intern2->email = "mary@usp.br";
$intern2->age = 27;
$intern2->save(); 

return redirect("/interns");
}
```

**Tip**
  
*Every time the interns/create route is accessed, the registrations will be made. You can delete everything before the insertions with the function: App\Models\Intern::truncate()*  
  
Finally, in the index view we can search for the registered interns and pass them as a variable to the template:

```php
public function index(){
return view('interns.index', [
'interns' => App\Models\Intern::all()
]);
}
```

In the blade, we list the interns:

```html
<ul>
@foreach($interns as $interns)
<li>{{ $interns->name }} - {{ $interns->email }} - {{ $interns->age }} years</li>
@endforeach
</ul>
```

## Exercise 1 - Importing Data and Statistics with Laravel

Objective: Create a basic system in Laravel to import data from a CSV file and display statistics from that data in a view.

[https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv](https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv)  
  
1. Create the Model and Migration:  
  
Create a model called Exercise with a corresponding migration.   
In the migration, define the necessary fields based on the columns in the exercise.csv file.    
Run the migration to create the table in the database.  
  
2. Create the Controller and Route for Importing  
  
Create a controller called ExerciseController with the importCsv method.   
Define a route exercises/importcsv that points to the importCsv method of the controller.    
In the importCsv method, implement the logic to read the exercise.csv file and save the data to the database using the Exercise model.  
  
**Tip:** *You can use the League\Csv\Reader class (available via Composer) to make it easier to read the CSV.*  
  
3. Create the Route and Method for Statistics  
  
In the same ExerciseController, create a method called stats.  
Define a route exercises/stats that points to the stats method.  
In the stats method, calculate the average of the pulse column for the rests, walking and running cases, as shown in the table below.  
Pass this data to a view called resources/views/exercises/stats.blade.php and finally assemble the table with HTML.  
  
Example output:  

| exercise.csv |rest|walking|running|
|--------------|----|-------|-------|
|Number of rows| XX |   XX  |  XXX  |
|Average Pulse | XX |   XX  |  XXX  |

### Step-by-step resolution

1. Initial Preparation  
  
Install the League\CSV library:

```bash
composer require league/csv
```

2. Download and Prepare the CSV File  
  
Access the link in the browser:  
  
https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv
  
Right-click and select "Save as"  
  
Save the file as exercise.csv in the storage/app folder of your project  
  
3. Create Model and Migration  
  
Create the model with migration:

```bash
php artisan make:model Exercise -m
```

Edit the migration in (database/migrations/xxxx_create_exercises_table.php):

```php
$table->string('diet');
$table->integer('pulse');
$table->string('time');
$table->string('kind');
```

Run the migration:

```bash
php artisan migrate
```

4. Create the Controller  
  
Create the controller with the name ExerciseController:

```bash
php artisan make:controller ExerciseController
```

Edit the controller in (app/Http/Controllers/ExerciseController.php):

```php
use App\Models\Exercise;
use League\Csv\Reader;
```

Then create the importCsv method:

```php
public function importCsv()
{
// Configure the CSV reader
$csv = Reader::createFromPath(storage_path('app/exercise.csv'), 'r');
$csv->setHeaderOffset(0); // Ignore the header

// Clear the table before importing
Exercise::truncate();

// Import each row
foreach ($csv as $line) {
$exercise = new Exercise(); 
$exercise->diet = $line['diet'];
$exercise->pulse = $line['pulse'];
$exercise->time = $line['time'];
$exercise->kind = $line['kind'];
$exercise->save();
}

return redirect('/exercises/stats'); }
```

Now create the stats method: 

```php
public function stats() 
{ 
// Calculation of statistics 
$statistic = [ 
    'rest' => [ 
        'amount' => Exercise::where('kind', 'rest')->count(), 
        'average_pulse' => Exercise::where('kind', 'rest')->avg('pulse') 
    ], 
    'walking' => [ 
        'amount' => Exercise::where('kind', 'walking')->count(), 
        'average_pulse' => Exercise::where('kind', 'walking')->avg('pulse') 
    ], 
    'running' => [ 
        'amount' => Exercise::where('kind', 'running')->count(), 
        'average_pulse' => Exercise::where('kind', 'running')->avg('pulse') 
    ] 
]; 

return view('exercises.stats', ['data' => $statistic]); 
}
```

5. Configure Routes  
  
Edit the routes in (routes/web.php): 

```php
use App\Http\Controllers\ExerciseController; 

Route::get('/exercises/importcsv', [ExerciseController::class, 'importCsv']); 
Route::get('/exercises/stats', [ExerciseController::class, 'stats']);
```

6. Create the Statistics View  

Create the folder for the views:

```bash
mkdir -p resources/views/exercises
```

Create the resources/views/exercises/stats.blade.php file:

```bash
touch resources/views/exercises/stats.blade.php
```

Edit the stats.blade.php view: 

```html
<!DOCTYPE html> 
<html> 
<head> 
<title>Statistics</title> 
<style> 
table { border-collapse: collapse; width: 80%; margin: 20px auto; } 
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; } 
th { background-color: #f2f2f2; }
</style>
</head>
<body>
<h1 style="text-align: center;">Statistics</h1>

<table>
<tr>
<th>exercise.csv</th>
<th>rest</th>
<th>walking</th>
<th>running</th>
</tr>
<tr>
<td>Number of rows</td>
<td>{{ $data['rest']['amount'] }}</td>
<td>{{ $data['walking']['amount'] }}</td>
<td>{{ $data['running']['amount'] }}</td>
</tr>
<tr>
<td>Pulse Average</td>
<td>{{ round($data['rest']['average_pulse'], 1) }}</td>
<td>{{ round($data['walking']['average_pulse'], 1) }}</td>
<td>{{ round($data['running']['average_pulse'], 1) }}</td>
</tr>
</table>
</body>
</html>
```

7. Test the Application  
  
Start the development server:

```bash
php artisan serve
```

Access in the browser:  
  
To import the data:  
  
http://localhost:8000/exercises/importcsv  
  
To see the statistics:  
  
http://localhost:8000/exercises/stats