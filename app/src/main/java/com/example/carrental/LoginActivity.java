public class LoginActivity extends AppCompatActivity {
    EditText loginInput, loginPass;
    Button loginBtn;
    String URL = "https://yourdomain.com/login.php"; // change this too

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        loginInput = findViewById(R.id.loginInput);
        loginPass = findViewById(R.id.loginPass);
        loginBtn = findViewById(R.id.loginBtn);

        loginBtn.setOnClickListener(v -> loginUser());
    }

    private void loginUser() {
        String user = loginInput.getText().toString();
        String pass = loginPass.getText().toString();

        JSONObject jsonBody = new JSONObject();
        try {
            jsonBody.put("user", user);
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
                            Toast.makeText(this, "Login successful!", Toast.LENGTH_SHORT).show();
                            startActivity(new Intent(this, MainActivity.class));
                            break;
                        case "wrong_password":
                            Toast.makeText(this, "Wrong password", Toast.LENGTH_SHORT).show();
                            break;
                        case "not_found":
                            Toast.makeText(this, "User not found", Toast.LENGTH_SHORT).show();
                            break;
                        default:
                            Toast.makeText(this, "Login failed", Toast.LENGTH_SHORT).show();
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
