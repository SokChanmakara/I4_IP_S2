import { Args, Mutation, Query, Resolver } from '@nestjs/graphql';

// Booking interface should be outside the class
interface Booking {
  id: string;
  start_date: string;
  end_date: string;
  hotel_id: string;
  is_checked_in: boolean;
  price: number;
}

@Resolver('Book')
export class BookResolver {
  private books = [
    {
      id: 1,
      title: 'Mathematic',
      author: 'Dara',
      price: 10,
    },
    {
      id: 2,
      title: 'Physic',
      author: 'Sok',
      price: 20,
    },
    {
      id: 3,
      title: 'Chemistry',
      author: 'Ratha',
      price: 15,
    },
  ];
  @Query('books')
  getAllBooks() {
    return this.books;
  }

  @Query('book')
  getBookById(@Args('id') id: number) {
    return this.books.find((book) => book.id == id);
  }

  @Mutation('addBook')
  addBook(@Args('title') title: string, @Args('price') price: number) {
    const sortedBooks = this.books.sort((a, b) => a.id - b.id);
    const lastId =
      sortedBooks.length > 0 ? sortedBooks[sortedBooks.length - 1].id : 0;
    const newBook = {
      id: lastId + 1,
      title,
      price,
      author: 'Unknown',
    };
    this.books.push(newBook);
    return newBook;
  }
  @Mutation('updateBook')
  updateBook(
    @Args('id') id: number,
    @Args('title') title: string,
    @Args('price') price: number,
  ) {
    const bookIndex = this.books.findIndex((book) => book.id == id);
    if (bookIndex === -1) {
      throw new Error('Book not found');
    }
    const updatedBook = {
      ...this.books[bookIndex],
      title,
      price,
    };
    this.books[bookIndex] = updatedBook;
    return updatedBook;
  }
  @Mutation('deleteBook')
  deleteBook(@Args('id') id: number) {
    try {
      const bookIndex = this.books.findIndex((book) => book.id == id);
      if (bookIndex === -1) {
        return false;
      }
      this.books.splice(bookIndex, 1);
      return true;
    } catch (e) {
      console.error(e);
      return false;
    }
  }

  // Booking logic
  private bookings: Booking[] = [];
  private bookingIdCounter = 1;

  @Mutation('bookHotel')
  bookHotel(
    @Args('hotel_id') hotel_id: string,
    @Args('start_date') start_date: string,
    @Args('end_date') end_date: string,
    @Args('price') price: number,
  ): Booking {
    const booking: Booking = {
      id: String(this.bookingIdCounter++),
      hotel_id,
      start_date,
      end_date,
      is_checked_in: false,
      price,
    };
    this.bookings.push(booking);
    return booking;
  }

  @Mutation('cancelBooking')
  cancelBooking(@Args('id') id: string): boolean {
    const idx = this.bookings.findIndex((b) => b.id === id);
    if (idx === -1) return false;
    this.bookings.splice(idx, 1);
    return true;
  }

  @Mutation('checkIn')
  checkIn(@Args('id') id: string): Booking | null {
    const booking = this.bookings.find((b) => b.id === id);
    if (!booking) return null;
    booking.is_checked_in = true;
    return booking;
  }

  @Query('bookingsByDateRange')
  bookingsByDateRange(
    @Args('start_date') start_date: string,
    @Args('end_date') end_date: string,
  ): Booking[] {
    return this.bookings.filter(
      (b) => b.start_date >= start_date && b.end_date <= end_date,
    );
  }
}
