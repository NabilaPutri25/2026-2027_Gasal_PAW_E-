<form action="processData_form.php" method="POST">
<fieldset>
    <legend>Person Details</legend>
    Surname<br>
    <input type="text" name="surname" size="30"><br>
    Email address<br>
    <input type="text" name="email" size="30"><br>
    Password<br>
    <input type="password" name="password" size="30"><br>
    Street Address<br>
    <textarea name="address" rows="2" cols="30"></textarea><br>
    State<br>
    <select name="state">
        <option value="-1">Choose your state</option>
        <option value="1">Queensland</option>
        <option value="2">Victoria</option>
        <option value="3">Tasmania</option>
        <option value="4">Western Australia</option>
        <option value="South Australia">South Australia</option>
        <option value="New South Wales">New South Wales</option>
        <option value="Northern Territory">Northern Territory</option>
    </select><br>
    <input type="hidden" name="country" value="Australia">
    Gender<br>
    <input type="radio" name="gender" value="Male">Male
    <input type="radio" name="gender" value="Female">Female<br>
    Vegetarian?<br>
    <input type="checkbox" name="vegetarian" value="Yes"><br><br>
    <input type="submit" value="Submit">
    <input type="reset" value="Reset">
</fieldset>
</form>
