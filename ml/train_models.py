import pandas as pd
import numpy as np
from sklearn.cluster import KMeans
from sklearn.ensemble import RandomForestClassifier
from sklearn.tree import DecisionTreeClassifier
from sklearn.preprocessing import LabelEncoder

# 1. Load Data
df = pd.read_csv('customers_dataset.csv')
print("Data Loaded.")

# 2. Preprocessing
# Encode 'Profession' as it is categorical
le = LabelEncoder()
df['Profession_Encoded'] = le.fit_transform(df['Profession'])

# Features for Clustering (we focus on Income and Spending Score for Segmentation)
X_cluster = df[['Annual_Income_k$', 'Spending_Score', 'Age']]

# 3. K-Means Clustering (Segmentation)
# Let's assume we want 3 Segments: Low, Medium, High Value
kmeans = KMeans(n_clusters=3, random_state=42, n_init=10)
df['Cluster'] = kmeans.fit_predict(X_cluster)

# Map Clusters to Logic (Heuristic Mapping)
# We can't know for sure which cluster is which without looking, so we use a heuristic:
# The cluster with the highest average Spending Score is 'High'
cluster_means = df.groupby('Cluster')['Spending_Score'].mean().sort_values()
# Sort clusters by spending score: Low -> Medium -> High
cluster_map = {
    cluster_means.index[0]: 'Low',
    cluster_means.index[1]: 'Medium',
    cluster_means.index[2]: 'High'
}
df['Loyalty_Level'] = df['Cluster'].map(cluster_map)

print("Clustering Complete. Segments Identified.")

# 4. Define Target Variables for Classification/Recommendation rules (Supervised learning simulation)
# In a real scenario, we might have historical labels. Here we derive them from the cluster logic
# to train the models as requested by the prompt (Demonstrating ML workflow).

def assign_discount(row):
    if row['Loyalty_Level'] == 'High':
        return 20 # 20% Discount
    elif row['Loyalty_Level'] == 'Medium':
        return 10 # 10% Discount
    else:
        return 5 # 5% Discount

def assign_card(row):
    if row['Loyalty_Level'] == 'High' and row['Spending_Score'] > 80:
        return 'VIP'
    elif row['Loyalty_Level'] == 'High':
        return 'Gold'
    elif row['Loyalty_Level'] == 'Medium':
        return 'Silver'
    else:
        return 'None'

df['Recommended_Discount'] = df.apply(assign_discount, axis=1)
df['Special_Card'] = df.apply(assign_card, axis=1)

# 5. Train Prediction Models (As requested)
# Predict Loyalty Level based on features
X_train = df[['Age', 'Annual_Income_k$', 'Spending_Score', 'Work_Experience', 'Profession_Encoded']]
y_loyalty = df['Loyalty_Level']
y_discount = df['Recommended_Discount']

# Random Forest for Loyalty Classification
rf_model = RandomForestClassifier(n_estimators=50, random_state=42)
rf_model.fit(X_train, y_loyalty)

# Decision Tree for Discount Recommendation
dt_model = DecisionTreeClassifier(random_state=42)
dt_model.fit(X_train, y_discount)

# 6. Generate Predictions CSV for Database Import
# We output the processed dataframe which acts as our "Predictions"
output_df = df[['Customer_ID', 'Loyalty_Level', 'Recommended_Discount', 'Special_Card']]
output_df.to_csv('predictions.csv', index=False)

print("Models Trained.")
print("Predictions saved to predictions.csv")
print(output_df.head())
