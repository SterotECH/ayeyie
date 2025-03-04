# Ayeyie Poultry Feed Software

A final-year project by **Oseiwusu Andrews** (Index: 5211040576), supervised by **Dr. Oliver Kuffour Boansi**, to develop an integrated payment and pickup software for Ayeyie Poultry Feed Depot. Built with Laravel 12 and Livewire, this system addresses operational inefficiencies, fraud, and customer trust issues caused by separate payment and pickup processes.

## Project Overview

Ayeyie Poultry Feed Depot faces challenges with its fragmented payment and pickup system, leading to receipt tampering, theft, and poor customer satisfaction. This software unifies these processes into a secure, efficient platform, enhancing operational integrity and user experience.

## Features

### Core Features

- **Integrated Payment and Pickup**: Combines payment processing and pickup verification into a seamless workflow.
- **Real-Time Transaction Logging**: Tracks all transactions instantly with detailed audit trails.
- **Secure Receipt Generation**: Produces tamper-proof receipts with unique codes.

### Standout Enhancements

- **QR Code Receipts (Top Feature)**: Generates QR codes on receipts, scannable at pickup to prevent fraud (e.g., falsified receipts).
- **Fraud Detection**: Logs suspicious activities (e.g., repeated payment failures) with severity levels and real-time admin alerts.
- **Offline Mode with Sync**: Operates during internet outages, syncing data when connectivity returns.
- **Predictive Inventory Alerts**: Monitors stock levels and alerts staff when below thresholds (e.g., "Premium Feed below 50 units").
- **Multilingual Support**: Offers UI in multiple languages (e.g., English, Twi) based on user preferences.
- **Customer Notifications**: Sends SMS/email updates (e.g., "Order #123 ready") via Laravel’s notification system.

## Tech Stack (Planned)

- **Backend**: Laravel 12 (PHP framework, releasing Feb 24, 2025).
- **Frontend**: Livewire (dynamic UI without a separate JS framework).
- **Database**: MySQL (3NF schema).
- **Tools**: Git (version control), Composer (dependencies), Twilio/Mailgun (notifications), QR code library.

## Database Schema

Designed in 3rd Normal Form (3NF) for integrity and scalability:

- **Users**: Staff/admin accounts with roles and language preferences.
- **Customers**: Customer details for transactions and notifications.
- **Products**: Inventory with pricing and stock thresholds.
- **Transactions**: Payment records with offline sync (`is_synced`).
- **Transaction_Items**: Links transactions to products with historical pricing.
- **Receipts**: Secure receipts with QR codes (`qr_code` field).
- **Pickups**: Pickup status with staff assignment and sync support.
- **Suspicious_Activities**: Fraud logs with severity (e.g., 'low', 'high').
- **Stock_Alerts**: Low stock warnings with timestamps.
- **Audit_Logs**: System action audit trail.
- **Notifications**: Laravel’s built-in table for customer SMS/email.

See `database/schema.sql` for the full schema.

## Setup Instructions

### Current Environment (Pre-Laravel 12)

1. **Directory Structure**:

   ```bash
   mkdir ~/ayeyie-poultry-feed-software && cd ~/ayeyie-poultry-feed-software
   mkdir docs database scripts
