spotify-clone/
│
├── index.php                   # Landing page (login or home)
├── dashboard.php               # User main dashboard (stats & recommendations)
├── admin_dashboard.php         # Admin dashboard (overall stats, user/song management)
├── play.php                    # Music player page
├── search.php                  # Search functionality for users
├── like.php                    # Handle user "like" action (AJAX/PHP)
├── skip.php                    # Handle user "skip" action (AJAX/PHP)
├── login.php                   # User/admin login system
├── register.php                # User registration page
├── logout.php                  # End session (logout)
│
├── manage_songs.php            # Admin page to add/edit/delete songs
├── manage_users.php            # Admin page to manage users (view, anonymize, etc.)
│
├── assets/
│   ├── css/
│   │   └── style.css           # Custom styles
│   ├── js/
│   │   └── script.js           # JavaScript (AJAX, UI handlers)
│   └── images/                 # Icons, album art, etc.
│
├── includes/
│   ├── db.php                  # MySQL connection
│   ├── header.php              # Common header (check user/admin session here)
│   └── footer.php              # Common footer
│
├── api/
│   ├── get_recommendations.php # Return song recommendations (AJAX)
│   ├── log_activity.php        # Store user play, like, skip actions
│   └── search_songs.php        # Return search results (AJAX)
│
├── gdpr/
│   ├── consent.php             # GDPR consent opt-in form
│   └── anonymize.php           # User anonymization (simulate GDPR compliance)
│
├── sql/
│   └── schema.sql              # Database schema (users, songs, logs, consent)
│
├── README.md
└── .htaccess                   # Optional, for URL rewrites or access control
