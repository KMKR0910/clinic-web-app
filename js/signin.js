function ValidateForm() {
    if (document.myform.name.value == "") {
      alert("Please enter your name");
      return false;
    }
    if (document.myform.tel.value == "") {
      alert("Please enter your contact details");
      return false;
    }
    if (document.myform.appointmet.value == "") {
      alert("Please enter your Gmail");
      return false;
    }
    if (document.myform.password.value == "") {
      alert("Please enter your password");
      return false;
    }
    window.location.href ="../pages/patient/patient-dashboard.php"; // Redirect to the dashboard
  }
  
  
  