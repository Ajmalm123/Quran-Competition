export interface Participant {
  id: number;
  name: string;
  place?: string;
  locality?: string;
  territory?: string;
}

// Local sample data for the prototype & testing rehearsals.
const SAMPLE_DATA: { name: string; place: string }[] = [
  { name: "Aarav Menon", place: "Kozhikode" },
  { name: "Isabella Rossi", place: "Rome" },
  { name: "Mohammed Rafi", place: "Malappuram" },
  { name: "Priya Nambiar", place: "Kannur" },
  { name: "Lucas Fernandez", place: "Madrid" },
  { name: "Ananya Krishnan", place: "Thrissur" },
  { name: "Oliver Bennett", place: "London" },
  { name: "Fatima Al-Sayed", place: "Dubai" },
  { name: "Rahul Varma", place: "Kochi" },
  { name: "Sofia Andersson", place: "Stockholm" },
  { name: "Joseph Thomas", place: "Kottayam" },
  { name: "Meera Pillai", place: "Trivandrum" },
  { name: "Daniel Okafor", place: "Lagos" },
  { name: "Nadia Hussain", place: "Doha" },
  { name: "Arjun Das", place: "Palakkad" },
  { name: "Emily Carter", place: "New York" },
  { name: "Suresh Kumar", place: "Kollam" },
  { name: "Hana Yoshida", place: "Tokyo" },
  { name: "Anwar Sadath", place: "Manjeri" },
  { name: "Grace D'Souza", place: "Mangalore" },
  { name: "Vikram Iyer", place: "Chennai" },
  { name: "Chloe Martin", place: "Paris" },
  { name: "Faisal Abdulla", place: "Riyadh" },
  { name: "Lakshmi Warrier", place: "Alappuzha" },
  { name: "Thomas Müller", place: "Munich" },
  { name: "Zainab Khan", place: "Abu Dhabi" },
  { name: "Karthik Raman", place: "Bengaluru" },
  { name: "Amelia Brooks", place: "Sydney" },
  { name: "Shahid Ibrahim", place: "Kasargod" },
  { name: "Diya Chandran", place: "Wayanad" },
  { name: "Noah Williams", place: "Toronto" },
  { name: "Revathi Nair", place: "Pathanamthitta" },
  { name: "Gabriel Costa", place: "São Paulo" },
  { name: "Aisha Rahman", place: "Muscat" },
  { name: "Benjamin Cole", place: "Chicago" },
  { name: "Sneha Mathew", place: "Idukki" },
];

export const PARTICIPANTS: Participant[] = SAMPLE_DATA.map((item, i) => ({
  id: i + 1,
  name: item.name,
  place: item.place,
}));
