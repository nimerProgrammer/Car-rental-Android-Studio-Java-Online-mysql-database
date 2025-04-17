public class RegisterActivity extends AppCompatActivity {
    EditText fullname, email, username, password;
    Button registerBtn;
    String URL = "https://yourdomain.com/register.php"; // change to your real URL

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        fullname = findViewById(R.id.fullname);
        email = findViewById(R.id.email);
        username = findViewById(R.id.username);
        password = findViewById(R.id.password);
        registerBtn = findViewById(R.id.registerBtn);

        registerBtn.setOnClickListener(v -> {
            registerUser();
        });
    }

    private void registerUser() {
        String name = fullname.getText().toString();
        String em = email.getText().toString();
        String user = username.getText().toString();
        String pass = password.getText().toString();

        JSONObject jsonBody = new JSONObject();
        try {
            jsonBody.put("fullname", name);
            jsonBody.put("email", em);
            jsonBody.put("username", user);
            jsonBody.put("password", pass);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest request = new JsonObjectRequest(Request.Method.POST, URL, jsonBody,
            response -> {
                try {
                    String status = response.getString("status");
                    switch (status) {
                        case "success":
                            Toast.makeText(this, "Registered successfully!", Toast.LENGTH_SHORT).show();
                            break;
                        case "email_exists":
                            Toast.makeText(this, "Email already exists", Toast.LENGTH_SHORT).show();
                            break;
                        case "username_exists":
                            Toast.makeText(this, "Username already exists", Toast.LENGTH_SHORT).show();
                            break;
                        default:
                            Toast.makeText(this, "Something went wrong", Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            },
            error -> Toast.makeText(this, "Error: " + error.toString(), Toast.LENGTH_SHORT).show()
        );

        Volley.newRequestQueue(this).add(request);
    }
}
