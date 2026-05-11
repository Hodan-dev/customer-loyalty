import pandas as pd
import numpy as np
import random

# Set random seed for reproducibility
np.random.seed(42)

# Define configurations
NUM_CUSTOMERS = 200
profession_list = ['Student', 'Engineer', 'Doctor', 'Artist', 'Teacher', 'Business', 'Other']

# Generate Synthetic Data
data = {
    'Customer_ID': range(1, NUM_CUSTOMERS + 1),
    'Age': np.random.randint(18, 70, size=NUM_CUSTOMERS),
    'Annual_Income_k$': np.random.randint(15, 150, size=NUM_CUSTOMERS),
    'Spending_Score': np.random.randint(1, 100, size=NUM_CUSTOMERS),
    'Work_Experience': np.random.randint(0, 40, size=NUM_CUSTOMERS),
    'Profession': [random.choice(profession_list) for _ in range(NUM_CUSTOMERS)]
}

df = pd.DataFrame(data)

# Save to CSV
df.to_csv('customers_dataset.csv', index=False)
print("Synthetic Dataset Generated: customers_dataset.csv")
print(df.head())
