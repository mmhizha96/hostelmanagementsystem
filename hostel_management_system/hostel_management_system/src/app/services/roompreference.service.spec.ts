import { TestBed } from '@angular/core/testing';

import { RoompreferenceService } from './roompreference.service';

describe('RoompreferenceService', () => {
  let service: RoompreferenceService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(RoompreferenceService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
