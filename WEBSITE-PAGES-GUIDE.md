# Sunshine Child-Care Nursery — Website Pages Guide

A simple guide to each page: purpose, who uses it, main actions, key data, and how pages connect. Written for both technical and non-technical readers.

---

## 1. Home (index.html)

**Purpose**  
The main landing page. It introduces Sunshine Child-Care Nursery, its values and services, and guides visitors to the rest of the site.

**Who uses it**  
Prospective and current parents, guardians, and anyone looking for nursery information.

**Main actions**
- Read the welcome message and key information.
- Use calls to action to contact the nursery or schedule a tour.
- Navigate to other sections via the main menu.

**Key data**
- Welcome header and short intro.
- 30+ years of experience.
- Message from the Nursery Manager.
- Ofsted “Good” rating.
- Contact and tour booking prompts.

**Connections**
- Links to **Contact Us**, **Schedule a Tour**, **Admissions & Fees**, **Why Choose Us**, and other main pages.

---

## 2. Our School (our-school.html)

**Purpose**  
Explains the nursery’s history and its link with Thames View Primary School: long-standing presence in the community and benefits of the location.

**Who uses it**  
Prospective and current parents wanting to understand the setting and its relationship with the school.

**Main actions**
- Read about the nursery’s history and location.
- Understand how the nursery works with Thames View Primary School.
- See how the setting supports a smooth move to school.

**Key data**
- Warm welcome from the Nursery Manager.
- 30+ year legacy.
- Clarification that the nursery runs independently alongside the school.
- Benefits of being next to the primary school (e.g. transition).

**Connections**
- Complements **Why Choose Us** and **Meet the Team**; can lead to **Admissions & Fees** or **Schedule a Tour**.

---

## 3. Our Values (our-values.html)

**Purpose**  
Meant to show the nursery’s core values and educational approach.

**Who uses it**  
Prospective and current parents interested in ethos and values.

**Main actions**
- Read about values and philosophy.
- *(Note: This page currently uses generic/placeholder content such as “The best online learning resource center”, “Students Enrolled”, “Certified Teachers”, etc., and does not yet reflect Sunshine Child-Care Nursery–specific values or team.)*

**Key data**
- Placeholder sections on courses, teachers, and student feedback rather than nursery-specific content.

**Connections**
- Should align with **Why Choose Us** and **Academic Excellence** once real content is added.

---

## 4. Meet the Team (meet-the-team.html)

**Purpose**  
Introduces key staff to build trust and show the expertise behind the nursery.

**Who uses it**  
Prospective and current parents.

**Main actions**
- View staff profiles and roles.
- Understand who leads parent partnership, curriculum, operations, and day-to-day care.

**Key data**
- Staff profiles (e.g. Emma Black, Kelly Gorman, Olivia Armstrong, Sophie Marshall).
- Roles and focus (e.g. parent partnership, curriculum, operations, nurturing environment).

**Connections**
- Supports **Why Choose Us** and **Schedule a Tour** (meet the team in person); often visited before **Admissions & Fees** or **Contact Us**.

---

## 5. Why Choose Us (why-choose-us.html)

**Purpose**  
Sets out the main reasons to choose Sunshine: history, vision, values, parent partnership, food, and performance.

**Who uses it**  
Prospective parents comparing nurseries.

**Main actions**
- Read the nursery’s story, vision, and values.
- See how the nursery works with families (Key Person, Learning Journals, Open Classrooms).
- Learn about nutrition and key metrics (e.g. enrollment, spaces, Ofsted, years serving families).
- Use calls to action to book a visit or download the curriculum guide.

**Key data**
- Story (30+ years), vision (“To create a space where learning feels like play”), values (Care & Safety, Curiosity & Creativity, Community & Partnership).
- Family partnership approach.
- Home-cooked meals and performance stats.
- Links to book a visit and download the curriculum.

**Connections**
- Links to **Schedule a Tour**, **Downloads**, **Admissions & Fees**, and **Academic Excellence**.

---

## 6. Academic Excellence (academic-excellence.html)

**Purpose**  
Describes the educational approach, curriculum, and a typical day so parents see how learning and care are delivered.

**Who uses it**  
Prospective and current parents interested in learning and routine.

**Main actions**
- Read about the holistic, play-based and structured approach.
- See curriculum areas: Play-Based Learning, Early Literacy & Numeracy, Creative Arts & Music, Outdoor Exploration, Social & Emotional Growth.
- Review “Daily Learning Highlights” and “A Child’s Day at Sunshine”.

**Key data**
- Approaches: Play-Based Learning, Early Literacy & Numeracy, Creative Arts & Music, Outdoor Exploration, Social & Emotional Growth.
- Daily highlights: Circle Time, Small Group Activities, Outdoor Play, Creative Corners.
- Example schedule: Breakfast Club, Preschool Session, After School Club.

**Connections**
- Ties to **Why Choose Us** and **Downloads** (curriculum/parent handbook); supports **Admissions & Fees** and **FAQ**.

---

## 7. Admissions & Fees (admissions-fees.html)

**Purpose**  
Explains how to join the nursery, the fee structure, and provides an online application form.

**Who uses it**  
Prospective parents and guardians ready to apply.

**Main actions**
- Read why choose Sunshine (Ofsted, curriculum, family partnership).
- See fees: Breakfast Club (£8.50), Preschool (£8.50), After School Club (£13.50), with a 30% discount for all three.
- Follow the 4-step admissions process (Application → Consultation → Enrolment Details → Secure place with £25 non-refundable fee).
- Submit the online application form (parent/guardian and child details, session preference, consent for terms and privacy).

**Key data**
- Fee breakdown and discount.
- Steps of the admissions process.
- Form fields: parent/guardian and child information, session preference, terms and privacy consent.
- Form is typically processed via backend (e.g. `process-admission.php`).

**Connections**
- Links to **Contact Us**, **Schedule a Tour**, **Terms & Conditions**, **Privacy Policy**, **FAQ** (fees), and **Downloads**.

---

## 8. Contact Us (contact-us.html)

**Purpose**  
Central contact page: form plus all contact and location details.

**Who uses it**  
Anyone with a question or enquiry (parents, guardians, job applicants, etc.).

**Main actions**
- Submit the contact form (name, email, phone, subject, message).
- View phone number (e.g. 07947 118506), email addresses (e.g. info@, hr.admin@, nurserymanager@), and address (Thames View Primary School, Rainham).
- Use the embedded map for location.

**Key data**
- Form: name, email, phone, subject, message.
- Phone, emails, physical address.
- Embedded map; form often submitted via AJAX to a backend (e.g. `send-message.php`).

**Connections**
- Linked from most pages; leads to **Schedule a Tour**, **Admissions & Fees**, **FAQ**, and **Downloads** as needed.

---

## 9. Schedule a Tour (schedule-tour.html)

**Purpose**  
Lets parents book a visit to see the nursery and meet staff.

**Who uses it**  
Prospective parents who want to see the setting before applying.

**Main actions**
- Use the embedded Calendly widget to choose and book a tour slot.
- Read “What to Expect on Your Tour” (tour of facilities, meet the team, ask questions).
- Use alternative contact (phone, email) if they need help booking.

**Key data**
- Calendly embed (third-party scheduling).
- Short description of the tour experience.
- Alternative contact details.

**Connections**
- Linked from **Home**, **Why Choose Us**, **Admissions & Fees**, **Testimonials**; leads naturally to **Admissions & Fees** or **Contact Us**.

---

## 10. Downloads (downloads.html)

**Purpose**  
One place for parents and guardians to get important documents: policies, handbook, and Ofsted report.

**Who uses it**  
Prospective and current parents, and anyone needing official documents.

**Main actions**
- Download the Official Ofsted Report (PDF).
- Download the Safeguarding & Child Protection Policy (PDF).
- Download the Parent Handbook (PDF).
- Read a short summary of the nursery’s commitment to safeguarding and the Designated Safeguarding Lead (DSL) contact (name/email to be filled in).

**Key data**
- Links to PDFs: Ofsted Report, Safeguarding & Child Protection Policy, Parent Handbook.
- Safeguarding summary and DSL contact placeholders.

**Connections**
- Referenced from **Why Choose Us**, **Admissions & Fees**, **FAQ**, **Terms & Conditions**, and **Privacy Policy**.

---

## 11. FAQ (faq.html)

**Purpose**  
Answers common questions to give quick information and reduce repeat enquiries.

**Who uses it**  
Prospective and current parents with general questions.

**Main actions**
- Open accordion items to read answers on opening hours, additional needs, outings/trips, age groups, fees and funding, and settling in.
- Follow links to **Admissions & Fees** (fees) and **Contact Us** (further questions).

**Key data**
- Accordion-style Q&As.
- Topics: hours, additional needs, outings, ages, fees/funding, settling-in process.

**Connections**
- Links to **Admissions & Fees** and **Contact Us**; supports **Why Choose Us** and **Academic Excellence** by clarifying details.

---

## 12. Testimonials (testimonials.html)

**Purpose**  
Shows feedback from current and former parents to build trust and credibility.

**Who uses it**  
Prospective parents considering the nursery.

**Main actions**
- Read parent quotes, names, children’s ages, and star ratings.
- Use calls to action to book a tour or apply for admission.

**Key data**
- Testimonials with quote, parent name, child’s age, star rating.
- Themes: caring staff, learning, smooth transitions.

**Connections**
- Links to **Schedule a Tour** and **Admissions & Fees**; supports **Why Choose Us** and **Meet the Team**.

---

## 13. Gallery (gallery.html)

**Purpose**  
Visual overview of the nursery: environments, activities, and daily life.

**Who uses it**  
Prospective and current parents who want to see the setting and what children do.

**Main actions**
- Browse a grid of photos (indoor/outdoor areas, group activities, art, etc.).
- Click images to open a larger view (lightbox).

**Key data**
- Intro to the facilities.
- Grid of images; lightbox for full-size view.

**Connections**
- Complements **Our School**, **Why Choose Us**, and **Schedule a Tour** (see it in person).

---

## 14. Privacy Policy (privacy-policy.html)

**Purpose**  
Explains how the nursery collects, uses, and protects personal data under UK GDPR and the Data Protection Act 2018.

**Who uses it**  
Parents, guardians, staff, and visitors who want to understand data handling.

**Main actions**
- Read what data is collected (children, parents/carers, staff), why it’s used, who it’s shared with, how long it’s kept, and what rights people have.
- Find contact details for the Data Protection Lead and how to complain to the ICO.

**Key data**
- Types of data: children, parents/carers, staff.
- Legal bases: contractual necessity, legal obligation, vital interests, legitimate interests, consent.
- Sharing: Local Authorities, Ofsted, health professionals, emergency services, school partners.
- Retention, security, and rights (e.g. access, rectification, erasure, object).
- Cookies and website data; Data Protection Lead and ICO complaint info.

**Connections**
- Linked from footer and **Admissions & Fees** (form consent); works alongside **Terms & Conditions**.

---

## 15. Terms & Conditions (terms-conditions.html)

**Purpose**  
Sets out the contract between the nursery and parents/guardians for childcare services.

**Who uses it**  
Parents and guardians before and after enrolling; staff for reference.

**Main actions**
- Read policies on enrollment, fees, attendance, health, collection, behaviour, safeguarding, photos, complaints, withdrawal, liability, and changes to terms.
- Use contact details for questions.

**Key data**
- Enrollment and registration (availability, documents, right to refuse).
- Fees and payment (advance payment, methods, late charges, non-refundable absences, fee changes).
- Attendance and absences (notice, illness, extended absence).
- Health and medical (disclosure, immunisation, medication, emergencies).
- Collection and authorised persons (list, ID, late collection).
- Behaviour and discipline (positive approach, challenging behaviour, suspension/termination).
- Safeguarding (legal duty, procedures, training, information sharing).
- Photographs and media (consent, withdrawal, no photos of other children).
- Complaints and concerns (escalation).
- Withdrawal and termination (notice, reasons).
- Liability and insurance; changes to terms; contact details.

**Connections**
- Linked from **Admissions & Fees** (form consent) and footer; used with **Privacy Policy** and **Downloads** (policies).

---

## Summary Table

| Page               | Primary audience        | Main purpose                          |
|--------------------|-------------------------|---------------------------------------|
| Home               | All visitors            | Introduce nursery, drive actions      |
| Our School         | Prospective parents    | History and link to school             |
| Our Values         | Prospective parents    | Ethos (content currently placeholder)  |
| Meet the Team      | Prospective parents    | Build trust via staff                  |
| Why Choose Us      | Prospective parents    | Compare and decide                     |
| Academic Excellence| Parents                 | Explain learning and routine           |
| Admissions & Fees  | Prospective parents    | Apply and understand fees              |
| Contact Us         | Anyone                  | Enquiries and location                 |
| Schedule a Tour    | Prospective parents    | Book a visit                           |
| Downloads          | Parents                 | Policies, handbook, Ofsted             |
| FAQ                | Parents                 | Quick answers                          |
| Testimonials       | Prospective parents    | Social proof                           |
| Gallery            | Prospective parents    | Visual overview                        |
| Privacy Policy     | Parents, staff, visitors| Data protection                        |
| Terms & Conditions | Parents, staff          | Contractual terms                      |

---

*Document generated for Sunshine Child-Care Nursery website. Language kept simple and professional for technical and non-technical readers.*
